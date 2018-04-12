<?php

namespace App\Console\Commands;

use App\Model\Shop;
use App\Model\ShopTradeSetting;
use App\Model\ShopWithdraw;
use Illuminate\Console\Command;
use TaoTui\Cashier\Facades\Llpay;

class ShopSettled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ShopSettled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单结算';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->ShopBalanceSettled();
    }

    public function ShopBalanceSettled(){
        $shopModel = new Shop();
        $shops = $shopModel->where('balance','>',0)->get();
        foreach ($shops as $key => $val){
            $shopSettingModel = new ShopTradeSetting();
            $setting = $shopSettingModel->where(array('shop_id' => $val['id'], 'status' => 4))->first();
            if(!$setting)
                continue;

            $data = [
                'shop_id' => $val['id'],
                'amount' => $val['balance'],
                'poundage' => 0,//手续费
                'withdraw_sn'=>$this->getSn(),
                ];
            //生成唯一打款单号
            mt_srand((double) microtime() * 1000000);
            $dt = date('Ymd');
            $cashpay_sn = $dt . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
            $data['cashpay_sn'] = $cashpay_sn;
            $withdraw = ShopWithdraw::create($data);
            if(!$withdraw)
                continue;

            $data['col_userid'] = $setting['shop_id'];
            $data['money_order'] = $val['balance'];
            $data['mobile'] = $setting['mob_bind'];
            $data['created_at'] = time('YmdHis');
            $data['order_sn'] = $cashpay_sn;
            $response = Llpay::traderpayment($data);
            if($response['status'] == 1){ //分账成功
                $llresult = $response['result'];
                $withdraw['trade_sn'] = $llresult['oid_paybill'];
                $withdraw['status'] = 1;
                $withdraw->save();
                $shopModel->where('id',$val['id'])->update(array('balance' => 0.00));

            }

            break;
        }

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
