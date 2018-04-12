<?php

namespace App\Services\Shop;

use App\Model\ShopBank;
use App\Model\Region;
use App\Model\ShopTradeSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use TaoTui\Cashier\Facades\Llpay;
use Illuminate\Support\Facades\Redis;

class BankService
{
    protected $shopBankModel = '';
    public function __construct() {
        $this->shopBankModel = new ShopBank();
    }

    public function getBanks()
    {
        $shop_id = Auth::user()->shop_id;
        $bank = $this->shopBankModel->where('shop_id',$shop_id)->whereNull('deleted_at')->get()->toArray();
        return $bank;
    }


    public function getBankById($id)
    {
        $shop_id = Auth::user()->shop_id;
        $bank = $this->shopBankModel->where('id',$id)->where('shop_id',$shop_id)->first();
        $province = '';
        $city = '';
        $district = '';
        if($bank['bank_province'])
        {
            $province = Region::where('id',$bank['bank_province'])->first()->toArray();
        }
        if($bank['bank_city'])
        {
            $city = Region::where('id',$bank['bank_city'])->first()->toArray();
        }
        if($bank['bank_district'])
        {
            $district = Region::where('id',$bank['bank_district'])->first()->toArray();
        }
        if($province || $city || $district)
        {
            $bank['region'] = [
                $province,
                $city,
                $district
            ];
        }
        return $bank;
    }

    public function postBank($params)
    {
        $shop_id = Auth::user()->shop_id;
        //检查是否重复
        $bankinfo = $this->shopBankModel->where('shop_id', $shop_id)->where('bank_no', $params['bank_no'])->count();
        if ($bankinfo > 0) {
            return array('status' => FALSE, 'msg' => '该店铺已绑定过同样账号的银行卡');
        }

        $bankcount = $this->shopBankModel->where('shop_id', $shop_id)->count();
        if ($bankcount >= 3) {
            return array('status' => FALSE, 'msg' => '最多只能新增3张银行卡');
        }

        $bank = config('bank');
        $bank_code = isset($params['bank_code']) ? $params['bank_code'] : '';
        $bank_name = $bank_code ? $bank[$bank_code] : '';
        $province = 0;
        $city = 0;
        $district = 0;
        $region = isset($params['city_code']) ? $params['city_code'] : '';
        if($region)
        {
            $province = $region[0]['id'];
            $city = $region[1]['id'];
            $district = $region[2]['id'];
        }
        $data = [
            'shop_id' => $shop_id,
            'bank_name' => $bank_name,
            'bank_code' => $bank_code,
            'bank_no' => $params['bank_no'],
            'bank_account'=>$params['bank_account'],
            'brabank_name' => $params['brabank_name'],
            'bank_mobile' => $params['bank_mobile'],
            'bank_province' => $province,
            'bank_city' => $city,
            'bank_district' => $district,
        ];

        //连连绑卡验证
        $authResult = $this->bankcardopenauth($shop_id,$params);
        if($authResult['status'])
        {
            $result = $this->shopBankModel->create($data);
        }else{
            return ['status'=>false,'msg'=>$authResult['ret_msg']];
        }

        return ['status'=>true,'data'=>$result,'authResult'=>$authResult];
    }

    public function putBank($params,$id)
    {
        $shop_id = Auth::user()->shop_id;
        //检查是否重复
        $bankinfo = $this->shopBankModel->where('shop_id', $shop_id)->where('id','!=',$id)->where('bank_no', $params['bank_no'])->count();
        if ($bankinfo > 0) {
            return array('status' => FALSE, 'msg' => '该店铺已绑定过同样账号的银行卡');
        }

        $bank = $this->shopBankModel->where('shop_id',$shop_id)->where('id',$id)->first();
        if(!$bank)
        {
            return ['status'=>false,'msg'=>'该店铺没有此银行卡信息'];
        }
        $openAuth = 0;
        if($bank['status']==0){
            $openAuth = 1;
        }

        if($params['bank_code'])
        {
            $bank_list = config('bank');
            $bank->bank_code = $params['bank_code'];
            if(isset($bank_list[$params['bank_code']]) && $bank_list[$params['bank_code']])
            {
                $bank->bank_name = $bank_list[$params['bank_code']];
            }
        }
        if($params['bank_no'] && $bank['bank_no'] != $params['bank_no'])
        {
            $openAuth = 1;
            $bank->bank_no = $params['bank_no'];
        }
        if($params['bank_account'])
        {
            $openAuth = 1;
            $bank->bank_account = $params['bank_account'];
        }
        if($params['brabank_name'])
        {
            $openAuth = 1;
            $bank->brabank_name = $params['brabank_name'];
        }
        if($params['bank_mobile'] && $params['bank_mobile'] != $bank['bank_mobile'])
        {
            $openAuth = 1;
            $bank->bank_mobile = $params['bank_mobile'];
        }
        if($params['bank_type'])
        {
            $bank->bank_type = $params['bank_type'];
        }
        $region = isset($params['region']) ? $params['region'] : '';
        if($region)
        {
            $bank->bank_province = $region[0]['id'];
            $bank->bank_city = $region[1]['id'];
            $bank->bank_district = $region[2]['id'];
        }
        $result = $bank->save();

        $authResult = '';
        //未验证或者修改了银行卡信息 重新验证
        if($openAuth == 1){
            $authResult = $this->bankcardopenauth($shop_id,$params);
        }

        return ['status'=>true,'data'=>$result,'authResult'=>$authResult];
    }

    /**
     * @name 银行卡绑卡
     * @param $shop_id
     * @param $params
     * @return mixed
     */
    private function bankcardopenauth($shop_id,$params){

        $tradeSetting = ShopTradeSetting::where('shop_id',$shop_id)->first();
        $authParams = [
            'created_at'=>$tradeSetting['created_at'],
            'mobile' =>$tradeSetting['mob_bind'],
            'addr_pro'=>$tradeSetting['addr_pro'],
            'addr_city'=>$tradeSetting['addr_city'],
            'user_id' => $tradeSetting['shop_id'],
            'card_no' => $params['bank_no'],
            'bind_mob' => $params['bank_mobile']//银行卡绑定的手机号
        ];
        $authResult = Llpay::bankcardopenauth($authParams);
        if($authResult['status']){
            //存token
            Redis::set('lianlianpay:openauth_token'.$params['bank_mobile'],$authResult['token']);
            Redis::expire('lianlianpay:openauth_token'.$params['bank_mobile'],1800);

        }
        return $authResult;
    }


    public function deleteBank($id)
    {
        $shop_id = Auth::user()->shop_id;
        $data = [
            'deleted_at' => Carbon::now()
        ];
        $bank = $this->shopBankModel->where('id',$id)->where('shop_id',$shop_id)->first();
        //连连钱包解绑
        if($bank['status'] && $bank['no_agree']){
            $pwd_pay = ShopTradeSetting::where('shop_id',$shop_id)->value('pwd_pay');
            $params = ['pwd_pay'=>decrypt($pwd_pay),'user_id'=>$shop_id,'no_agree'=>$bank['no_agree']];
            $unbindRet = Llpay::bankcardunbind($params);
            if($unbindRet['status']){
                $bank->delete();
            }
        }else{
            $bank->delete();
        }
        return $bank;
    }


    /**
     * @name 个体工商户银行卡绑卡验证
     * @param $params
     * @return mixed
     */
    public function bankcardAuthVerfy($params){
        $shop_id = Auth::user()->shop_id;

        //超过 30 分钟,或者其他原因导致上送的授权令牌失效,需重新 发起签约接口获取授权令牌
        $token = Redis::get('lianlianpay:openauth_token'.$params['bank_mobile']);
        if(!$token){
            return ['status'=>false,'message'=>'token已过期，请重新获取验证码'];
        }
        $data = [
            'user_id' => $shop_id,
            'token' => $token,
            'verify_code' => $params['verify_code']
        ];
        //print_r($data);exit;
        $result = Llpay::bankcardauthverfy($data);

        //return ['status'=>true,'message'=>'绑卡验证成功','result'=>$result];

        //print_r($result);
        if($result['status'] == 1)
        {
            $shopBankModel = new ShopBank();
            //更新银行卡绑卡验证状态
            $shopBankModel->where('id',$params['bank_id'])->update(['status'=>1,'no_agree'=>$result['result']['no_agree']]);
            return ['status'=>true,'message'=>'绑卡验证成功','result'=>$result];
        }
        return ['status'=>false,'message'=>$result['ret_msg'],'result'=>$result];
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 非个体工商户银行卡添加
     */
    public function addBank($params)
    {
        $shop_id = Auth::user()->shop_id;

        $bank = config('bank');
        $bank_code = isset($params['bank_code']) ? $params['bank_code'] : '';
        $bank_name = $bank_code ? $bank[$bank_code] : '';
        $province = 0;
        $city = 0;
        $district = 0;
        $region = isset($params['city_code']) ? $params['city_code'] : '';
        if($region)
        {
            $province = $region[0]['id'];
            $city = $region[1]['id'];
            $district = $region[2]['id'];
        }
        $data = [
            'shop_id' => $shop_id,
            'bank_name' => $bank_name,
            'bank_code' => $bank_code,
            'bank_no' => $params['bank_no'],
            'bank_account'=>$params['bank_account'],
            'brabank_name' => $params['brabank_name'],
            'bank_mobile' => $params['bank_mobile'],
            'bank_province' => $province,
            'bank_city' => $city,
            'bank_district' => $district,
        ];
        $result = $this->shopBankModel->create($data);

        return ['status'=>true,'data'=>$result];
    }
}
