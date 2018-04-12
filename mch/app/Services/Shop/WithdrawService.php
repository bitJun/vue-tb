<?php

namespace App\Services\Shop;

use App\Model\ShopBank;
use App\Model\ShopWithdraw;
use Illuminate\Support\Facades\Auth;

class WithdrawService
{

    public function getWithdraws($params){

        $data['data'] = [];
        $data['_count'] = 0;

        $shop_id = isset($params['shop_id']) ? $params['shop_id'] : Auth::user()->shop_id;

        $query = ShopWithdraw::where('shop_id',$shop_id);

        if(isset($params['date_start']) && $params['date_start'])
        {
            $query->where('created_at','>=',$params['date_start']);
        }
        if(isset($params['date_end']) && $params['date_end'])
        {
            $query->where('created_at','<=',$params['date_end']);
        }
        if(isset($params['status']) && $params['status'] != 'all')
        {
            $query->where('status',$params['status']);
        }

        $data['_count'] = $query->count();
        if($data['_count'])
        {
            $data['data'] = $query->skip($params['offset'])->take($params['limit'])->orderBy('id','desc')->get()->toArray();
            $config = $this->getWithdrawStatus();
            foreach ($data['data'] as &$v)
            {
                $v['status_name'] = $config[$v['status']];
            }
        }

        return $data;
    }
    public function postWithdraw($params)
    {
        $shop_id = Auth::user()->shop_id;

        $bankInfo = ShopBank::where('shop_id', $shop_id)->where('id', $params['bank_id'])->first();
        if (!$bankInfo) {
            return array('status' => FALSE, 'msg' => '银行卡有误，请检查');
        }

        //提现金额判断
        if($params['amount'] <= 0.00){
            return array('status' => FALSE, 'msg' => '提现金额必须大于0');
        }
        //是否超出最大可提金额
        $shopService = new ShopService();
        $shop = $shopService->getShop($shop_id);
        if(!$shop){
            return array('status' => FALSE, 'msg' => '店铺有误');
        }
        if($shop['balance'] < $params['amount']){
            return array('status' => FALSE, 'msg' => '最多可提现'.$shop['balance']);
        }

        $data = [
            'shop_id' => $shop_id,
            'amount' => $params['amount'],
            'poundage' => 1.00,//手续费
            'withdraw_sn'=>$this->getSn(),
            'bank_name' => $bankInfo['bank_name'],
            'bank_no' => $bankInfo['bank_no'],
            //'bank_account' => $bankInfo['bank_account'],
            'brabank_name' => $bankInfo['brabank_name'],
            'bank_mobile' => $bankInfo['bank_mobile'],
            'province'=>$bankInfo['province'],
            'city'=>$bankInfo['city'],
            'district'=>$bankInfo['district'],

        ];

        //生成唯一打款单号
        mt_srand((double) microtime() * 1000000);
        $dt = date('Ymd');
        $data['cashpay_sn'] = $dt . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);


        $result = ShopWithdraw::create($data);
        if($result){
            //扣除金额
            $shopService->decBalance($shop_id,$params['amount']);
            //余额记录 type==2
            $detailData = [
                'order_sn'=>$data['withdraw_sn'],
                'cash' => $data['amount'],
                'balance'=>$shop['balance']-$data['amount'],
                'type'=>SHOP_BALANCE_DETAIL_WITHDRAW,
                'memo'=>'商家申请提现'
            ];
            $balanceDetailService = new BalanceDetailService();
            $balanceDetailService->postDetail($detailData);

            return ['status'=>true,'data'=>$result];
        }
        return ['status'=>false,'msg'=>'提现失败'];
    }

    public function getWithdrawStatus(){
        $config = config('status.shop_withdraw_status');
        return $config;
    }

    private function getSn(){
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $dt = date('Ymdhis');
        //$dt = Carbon::now()->setToStringFormat('Y-m-d');
        $sn = 'T' . $dt . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $result = ShopWithdraw::select('withdraw_sn')->where(array('withdraw_sn' => $sn))->first();
        if (!$result) {
            return $sn;
        }

        /* 如果有重复的，则重新生成 */
        return $this->getSn();
    }
}
