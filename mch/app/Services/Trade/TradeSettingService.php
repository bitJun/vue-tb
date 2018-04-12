<?php

namespace App\Services\Trade;

use App\Model\Region;
use App\Model\ShopBank;
use App\Model\ShopTradeSetting;
use App\Services\Shop\BankService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use TaoTui\Cashier\Facades\Llpay;

class TradeSettingService
{
    public function getLlpayInfo()
    {
        $shop_id = Auth::user()->shop_id;
        //基本信息
        $baseInfo = ShopTradeSetting::where('shop_id',$shop_id)->first();
        if($baseInfo)
        {
            $type_license_arr = [
                0=>'普通营业执照',
                1=>'多证合一营业执照(存在独立的组织机构代 码证)(合证不合号)',
                2=>'多证合一营业执照(不存在独立的组织机构 代码证)(合证合号)'
            ];
            $type_register_arr = [
                0=>'企业',
                1=>'个体工商户',
                2=>'事业单位',
                3=>'社会团体'
            ];
            $type_explicense_arr = [
                0=>'非永久',
                1=>'永久'
            ];
            $baseInfo['type_license_str'] = $type_license_arr[$baseInfo['type_license']];
            $baseInfo['type_register_str'] = $type_register_arr[$baseInfo['type_register']];
            $baseInfo['type_explicense_str'] = $type_explicense_arr[$baseInfo['type_explicense']];

            $baseInfo['path_front_card'] = getImageUrl($baseInfo['front_card']);
            $baseInfo['path_back_card'] = getImageUrl($baseInfo['back_card']);
            $baseInfo['path_copy_license'] = getImageUrl($baseInfo['copy_license']);
            $baseInfo['path_copy_org'] = getImageUrl($baseInfo['copy_org']);
            $baseInfo['path_bank_openlicense'] = getImageUrl($baseInfo['bank_openlicense']);

            unset($baseInfo['pwd_pay']);
        }
        //银行卡信息
        $bankInfo = ShopBank::where('shop_id',$shop_id)->first();
        if($bankInfo)
        {
            $bankInfo['bank_pro_str'] = Region::where('id',$bankInfo['bank_province'])->value('name');
            $bankInfo['bank_city_str'] = Region::where('id',$bankInfo['bank_city'])->value('name');
            $bankInfo['bank_district_str'] = Region::where('id',$bankInfo['bank_district'])->value('name');
        }
        $isDone = 1;
        if(!$baseInfo && !$bankInfo)
        {
            $isDone = 0;
        }elseif(isset($baseInfo['status']) && $baseInfo['status'] == 0)
        {
            $isDone = 0;
        }
        return ['isDone'=>$isDone,'baseInfo'=>$baseInfo,'bankInfo'=>$bankInfo];
    }

    public function smsSend($params)
    {
        $shop_id = Auth::user()->shop_id;
        $data = [
            'user_id' => $shop_id,
            'mobile' => $params['mob_bind']
        ];
        $result = Llpay::smsSend($data);
        if($result['status'] == 1)
        {
            //存手机号
            Redis::set('lianlianpay:mobile_'.$shop_id,$params['mob_bind']);

            //存第一次token
            Redis::set('lianlianpay:smssend_token'.$params['mob_bind'],$result['token']);
            return ['status'=>true,'message'=>'获取成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg']];
    }

    public function smsCheck($params)
    {
        $shop_id = Auth::user()->shop_id;
        $data = [
            'user_id' => $shop_id,
            'token' => $params['token'],
            'verify_code' => $params['verify_code']
        ];

        $result = Llpay::smscheck($data);

        if($result['status'] == 1)
        {
            //存第二次token
            Redis::set('lianlianpay:smscheck_token'.$shop_id,$result['token']);

            //存表
            $tradeSettingData = [
                'shop_id' => $shop_id,
                'mob_bind' => $params['mob_bind']
            ];
            $resCord = ShopTradeSetting::where('shop_id',$shop_id)->first();
            if(!$resCord)
            {
                ShopTradeSetting::create($tradeSettingData);
            }
            return ['status'=>true,'message'=>'验证成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg']];
    }

    public function openSmsUnitUser($params)
    {
        $shop_id = Auth::user()->shop_id;
        $data = [
            'type' => $params['type'],
            'name_user' => $params['name_user'],
            'no_idcard' => $params['no_idcard'],
            'exp_idcard' => $params['exp_idcard'],
            'addr_unit' => $params['addr_unit'],
            'busi_user' => $params['busi_user'],
            'name_unit' => $params['name_unit'],
            'num_license' => $params['num_license'],
            'type_register' => $params['type_register'],
            'type_license' => $params['type_license'],
            'exp_license' => $params['exp_license'],
            'type_explicense' => $params['type_explicense'],
            'exp_orgcode' => $params['exp_orgcode'],
            'org_code' => $params['org_code'],
            'status' => 0
        ];

        $pwdPay = $params['pwd_pay'];
        $newEncrypter = new \Illuminate\Encryption\Encrypter( md5('modian'), Config::get( 'app.cipher' ) );
        $data['pwd_pay'] = $newEncrypter->encrypt( $pwdPay );

        if($params['region_unit'])
        {
            $data['addr_pro'] = $params['region_unit'][0]['id'];
            $data['addr_pro_str'] = $params['region_unit'][0]['name'];
            $data['addr_city'] = $params['region_unit'][1]['id'];
            $data['addr_city_str'] = $params['region_unit'][1]['name'];
            $data['addr_district'] = $params['region_unit'][2]['id'];
            $data['addr_district_str'] = $params['region_unit'][2]['name'];
        }else{
            $data['addr_pro'] = 0;
            $data['addr_pro_str'] = '';
            $data['addr_city'] = 0;
            $data['addr_city_str'] = '';
            $data['addr_district'] = 0;
            $data['addr_district_str'] = '';
        }

        $city_code = 0;
        if($params['region_bank'])
        {
            $city_code = $params['region_bank'][1]['id'];
        }

        $mobile = Redis::get('lianlianpay:mobile_'.$shop_id);

        $tradeSetting = ShopTradeSetting::where('shop_id',$shop_id)->first();
        if(!$tradeSetting)
        {
            return ['status'=>false,'message'=>'请先进行手机号验证'];
        }

        if($mobile != $tradeSetting['mob_bind'])
        {
            return ['status'=>false,'message'=>'手机号码不匹配，请重新操作'];
        }

        $resCord = ShopTradeSetting::where('shop_id',$shop_id)->update($data);
        if($resCord)
        {
            //TODO 调用连连支付接口进行认证
            $data['created_at'] = $tradeSetting['created_at'];
            $data['mobile'] = $tradeSetting['mob_bind'];
            $data['mob_bind'] = $tradeSetting['mob_bind'];
            $data['user_id'] = $tradeSetting['shop_id'];

            $token = Redis::get('lianlianpay:smscheck_token'.$shop_id);
            $data['token'] = $token;
            $data['city_code'] = $city_code;
            $data['brabank_name'] = $params['brabank_name'];
            $data['card_no'] = $params['card_no'];
            $data['bank_code'] = $params['bank_code'];
            $data['type_expidcard'] = 0;
            if($params['region_unit'])
            {
                $data['addr_dist'] = $params['region_unit'][2]['id'];
            }else{
                $data['addr_dist'] = 0;
            }
            $data['pwd_pay'] = $pwdPay;

            $result = Llpay::opensmsunituser($data);

            if($result['status'] == 1)
            {
                ShopTradeSetting::where('shop_id',$shop_id)->update(['status'=>1]);
                return ['status'=>true,'message'=>'设置成功'];
            }
            return ['status'=>false,'message'=>$result['ret_msg'],'error'=>$result];
        }
        return ['status'=>false,'message'=>'设置失败'];
    }

    /**
     * @name 个人绑卡
     * @param $params
     * @return mixed
     */
    public function bankcardOpenAuth($params){

        $shop_id = Auth::user()->shop_id;
        $data = [
            'user_id' => $shop_id,
            'mobile' => $params['bind_mob'],
            'card_no' => $params['card_no']
        ];
        $result = Llpay::bankcardopenauth($data);
        if($result['status'] == 1)
        {
            //存token
            Redis::set('lianlianpay:openauth_token'.$params['bind_mob'],$result['token']);
            Redis::expire('lianlianpay:openauth_token'.$params['bind_mob'],1800);

            //存手机号
            Redis::set('lianlianpay:mobile_'.$shop_id,$params['bind_mob']);
            //存银行卡号
            Redis::set('lianlianpay:card_no_'.$shop_id,$params['card_no']);
            return ['status'=>true,'message'=>'操作成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg']];

    }


    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 修改基本信息验证
     */
    public function pwdAuth($params)
    {
        $shop_id = Auth::user()->shop_id;

        $tradeSetting = ShopTradeSetting::where('shop_id',$shop_id)->first();
        if(!$tradeSetting)
        {
            return ['status'=>false,'message'=>'没有找到交易设置信息，请先进行设置'];
        }
        if(!$tradeSetting['pwd_pay'])
        {
            return ['status'=>false,'message'=>'您还没有设置过交易密码'];
        }
        $newEncrypter = new \Illuminate\Encryption\Encrypter( md5('modian'), Config::get( 'app.cipher' ) );
        $decrypted = $newEncrypter->decrypt( $tradeSetting['pwd_pay'] );
        if($decrypted != $params['pwd_pay'])
        {
            return ['status'=>false,'message'=>'支付密码错误，请重新输入。'];
        }
        if($tradeSetting['num_license'] != $params['num_license'])
        {
            return ['status'=>false,'message'=>'营业执照号码错误，请重新输入。'];
        }

        $data = [
            'user_id' => $shop_id,
            'pwd_pay' => $params['pwd_pay'],
            'num_license' => $params['num_license'],
            'flag_check' => 0, //操作标识 0 通过营业执照号码+支付密码授权修改接口 1 通过原手机号码+支付密码授权修改接口
            'mobile' => $tradeSetting['mob_bind'],
            'created_at' => $tradeSetting['created_at'],
            'addr_pro' => $tradeSetting['addr_pro'],
            'addr_city' => $tradeSetting['addr_city'],
        ];
        $result = Llpay::pwdAuth($data);
        if($result['status'] == 1)
        {
            //存Token
            Redis::set('lianlianpay:pwdauth_token'.$shop_id,$result['token']);
            return ['status'=>true,'message'=>'信息通过'];
        }
        return ['status'=>false,'message'=>'输入信息有误'];
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 修改基本信息
     */
    public function modifyUnitUser($params)
    {
        $shop_id = Auth::user()->shop_id;

        $unitUser = ShopTradeSetting::where('shop_id',$shop_id)->first();
        if(!$unitUser)
        {
            return ['status'=>false,'message'=>'未找到当前记录'];
        }
        $data = [
            'name_user' => $params['name_user'],
            'no_idcard' => $params['no_idcard'],
            'exp_idcard' => $params['exp_idcard'],
            'addr_unit' => $params['addr_unit'],
            'busi_user' => $params['busi_user'],
            'name_unit' => $params['name_unit'],
            'num_license' => $params['num_license'],
            'type_register' => $params['type_register'],
            'type_license' => $params['type_license'],
            'org_code' => $params['org_code'],
            'exp_license' => $params['exp_license'],
            'type_explicense' => $params['type_explicense'],
            'exp_orgcode' => $params['exp_orgcode'],
        ];

        if($params['region_unit'])
        {
            $data['addr_pro'] = $params['region_unit'][0]['id'];
            $data['addr_pro_str'] = $params['region_unit'][0]['name'];
            $data['addr_city'] = $params['region_unit'][1]['id'];
            $data['addr_city_str'] = $params['region_unit'][1]['name'];
            $data['addr_district'] = $params['region_unit'][2]['id'];
            $data['addr_district_str'] = $params['region_unit'][2]['name'];
            $addr_dist = $params['region_unit'][2]['id'];
        }else{
            $data['addr_pro'] = 0;
            $data['addr_pro_str'] = '';
            $data['addr_city'] = 0;
            $data['addr_city_str'] = '';
            $data['addr_district'] = 0;
            $data['addr_district_str'] = '';
            $addr_dist = 0;
        }

        $paramsData = $data;
        $paramsData['addr_dist'] = $addr_dist;
        $paramsData['created_at'] = $unitUser['created_at'];
        $paramsData['mobile'] = $unitUser['mob_bind'];
        $paramsData['token'] = Redis::get('lianlianpay:pwdauth_token'.$shop_id);
        $paramsData['user_id'] = $shop_id;
        $paramsData['type_idcard'] = 0;
        $paramsData['type_expidcard'] = 0;

        $result = Llpay::modifyUnitUser($paramsData);
        if($result['status'] == 1)
        {
            ShopTradeSetting::where('shop_id',$shop_id)->update($data);
            return ['status'=>true,'message'=>'修改成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg'],'error'=>$result];
    }

    public function uploadUnitPhoto($params)
    {
        $shop_id = Auth::user()->shop_id;
        $params['user_id'] = $shop_id;
        if(!isset($params['front_card']['tmp_name']) && !isset($params['back_card']['tmp_name']) && !isset($params['copy_license']['tmp_name']) && !isset($params['copy_org']['tmp_name']) && !isset($params['bank_openlicense']['tmp_name']))
        {
            return ['status'=>false,'message'=>'没有要修改的图片','error'=>''];
        }
        $result = Llpay::uploadunitphoto($params);
        if($result['status'] == 1)
        {
            //存库
            $data = [
                'front_card' => $params['front_card']['img'],
                'back_card' => $params['back_card']['img'],
                'copy_license' => $params['copy_license']['img'],
            ];
            if(isset($params['copy_org']['img']))
            {
                $data['copy_org'] = $params['copy_org']['img'];
            }
            if(isset($params['bank_openlicense']['img']))
            {
                $data['bank_openlicense'] = $params['bank_openlicense']['img'];
            }
            ShopTradeSetting::where('shop_id',$shop_id)->update($data);

            //删除复制的临时文件
            if(isset($params['front_card']['tmp_name']) && $params['front_card']['tmp_name'] && file_exists($params['front_card']['tmp_name']))
            {
                unlink($params['front_card']['tmp_name']);
            }
            if(isset($params['back_card']['tmp_name']) && $params['back_card']['tmp_name'] && file_exists($params['back_card']['tmp_name']))
            {
                unlink($params['back_card']['tmp_name']);
            }
            if(isset($params['copy_license']['tmp_name']) && $params['copy_license']['tmp_name'] && file_exists($params['copy_license']['tmp_name']))
            {
                unlink($params['copy_license']['tmp_name']);
            }
            if(isset($params['copy_org']['tmp_name']) && $params['copy_org']['tmp_name'] && file_exists($params['copy_org']['tmp_name']))
            {
                unlink($params['copy_org']['tmp_name']);
            }
            if(isset($params['bank_openlicense']['tmp_name']) && $params['bank_openlicense']['tmp_name'] && file_exists($params['bank_openlicense']['tmp_name']))
            {
                unlink($params['bank_openlicense']['tmp_name']);
            }
            return ['status'=>true,'message'=>'上传成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg'],'error'=>$result];
    }


    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 对公账户修改
     */
    public function modifyUnitUserAcct($params)
    {
        $shop_id = Auth::user()->shop_id;

        $unitUser = ShopTradeSetting::where('shop_id',$shop_id)->first();
        if(!$unitUser)
        {
            return ['status'=>false,'message'=>'未找到当前记录'];
        }
        $data = [
            'bank_code' => $params['bank_code'],
            'brabank_name' => $params['brabank_name'],
            'bank_no' => $params['card_no'],
        ];
        $paramsData = $data;

        if($params['region_bank'])
        {
            $data['bank_province'] = $params['region_bank'][0]['id'];
            $data['bank_city'] = $params['region_bank'][1]['id'];
            $data['bank_district'] = $params['region_bank'][2]['id'];
            $paramsData['addr_pro'] = $params['region_bank'][0]['id'];
            $paramsData['addr_city'] = $params['region_bank'][1]['id'];
            $paramsData['city_code'] = $params['region_bank'][1]['id'];
        }else{
            $data['bank_province'] = 0;
            $data['bank_city'] = 0;
            $data['bank_district'] = 0;
            $paramsData['addr_pro'] = 0;
            $paramsData['addr_city'] = 0;
            $paramsData['city_code'] = 0;
        }

        $paramsData['created_at'] = $unitUser['created_at'];
        $paramsData['mobile'] = $unitUser['mob_bind'];
        $paramsData['token'] = Redis::get('lianlianpay:pwdauth_token'.$shop_id);
        $paramsData['user_id'] = $shop_id;
        $paramsData['card_no'] = $params['card_no'];
        $bank = config('bank');
        $paramsData['acct_name'] = $bank[$params['bank_code']];

        $result = Llpay::modiyunituseracct($paramsData);
        if($result['status'] == 1)
        {
            $shopBank = ShopBank::where('shop_id',$shop_id)->first();
            if($shopBank)
            {
                ShopBank::where('shop_id',$shop_id)->where('id',$shopBank['id'])->update($data);
            }else{
                $bankService = new BankService();
                $bankData = [
                    'bank_no' => $params['card_no'],
                    'bank_code' => $params['bank_code'],
                    'city_code' => $params['region_bank'],
                    'bank_account' => '',
                    'brabank_name' => $params['brabank_name'],
                    'bank_mobile' => $unitUser['mob_bind'],
                ];

                $bankService->addBank($bankData);
            }
            return ['status'=>true,'message'=>'修改成功'];
        }
        return ['status'=>false,'message'=>$result['ret_msg']];
    }

    public function getSingleUser()
    {
        $shop_id = Auth::user()->shop_id;
        $result = Llpay::singleuserquery(['user_id'=>$shop_id]);
        if($result['status'] == 1)
        {
            //更新状态为审核通过
            if($result['data']['kyc_status'] == 4)
            {
                ShopTradeSetting::where('shop_id',$shop_id)->update(['status'=>4]);
            }
            return $result;
        }
        return ['status'=>false,'message'=>$result['ret_msg']];
    }
}
