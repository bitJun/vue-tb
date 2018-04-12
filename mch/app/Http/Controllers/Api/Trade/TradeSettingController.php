<?php

namespace App\Http\Controllers\Api\Trade;

use App\Services\Shop\BankService;
use App\Services\Trade\TradeSettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TradeSettingController extends Controller
{
    public function __construct(TradeSettingService $tradeSettingService)
    {
        $this->tradeSettingService = $tradeSettingService;
    }

    public function getLlpayInfo()
    {
        $result = $this->tradeSettingService->getLlpayInfo();
        return Response::json($result);
    }

    public function smsSend(Request $request)
    {
        $rules = [
            'mob_bind'=>'required|regex:/^1\d{10}$/|unique:shop_trade_setting,mob_bind,NULL,id,status,1'
        ];
        $message = [
            'mob_bind.required'=>'手机号必须填写',
            'mob_bind.regex'=>'手机号格式不正确',
            'mob_bind.unique'=>'手机号已经被注册，请重新输入'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }
        $result = $this->tradeSettingService->smsSend($request->all());
        if(!$result['status'])
        {
            return Response::json(['message'=>$result['message']],422);
        }
        return Response::json($result);
    }

    public function smsCheck(Request $request)
    {
        $rules = [
            'mob_bind'=>'required|regex:/^1\d{10}$/',
            'verify_code'=>'required|numeric'
        ];
        $message = [
            'mob_bind.required'=>'手机号必须填写',
            'mob_bind.regex'=>'手机号格式不正确',
            'verify_code.required'=>'请填写验证码',
            'verify_code.numeric'=>'验证码格式不正确'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $token = Redis::get('lianlianpay:smssend_token'.$request->mob_bind);
        if(!$token)
        {
            return Response::json(['message'=>'请先获取手机短信验证码'],422);
        }

        $data = [
            'token' => $token,
            'verify_code' => $request->verify_code,
            'mob_bind' => $request->mob_bind
        ];
        $result = $this->tradeSettingService->smsCheck($data);
        if(!$result['status'])
        {
            return Response::json(['message'=>$result['message']],422);
        }
        return Response::json($result);
    }

    public function openSmsUnitUser(Request $request)
    {
        $rules = [
            'name_user' => 'required',
            'no_idcard' => 'required|id_card',
            'exp_idcard' => 'required',
            'addr_unit' => 'required',
            'region_unit' => 'required',
            'busi_user' => 'required',
            'name_unit' => 'required',
            'num_license' => 'required',
            'type_register' => 'required',
            'type_license' => 'required',
            'pwd_pay' => 'required|between:6,20',
            'region_bank' => 'sometimes|required_if:type_register,0,2,3',
            'brabank_name' => 'sometimes|required_if:type_register,0,2,3',
            'bank_code' => 'sometimes|required_if:type_register,0,2,3',
            'card_no' => 'sometimes|required_if:type_register,0,2,3',
            'type_explicense' => 'required',
        ];
        $message = [
            'type.in'=>'申请类型请选择为企业',
            'name_user.required' => '请填写企业法人',
            'no_idcard.required' => '请填写法人身份证',
            'no_idcard.id_card' => '身份证格式错误',
            'exp_idcard.required' => '请填写法人身份证有效期',
            'addr_unit.required' => '请填写企业地址',
            'region_unit.required' => '请选择所在企业所在地',
            'busi_user.required' => '请填写经营范围',
            'name_unit.required' => '请填写企业名称',
            'num_license.required' => '请填写营业执照号码',
            'type_register.required' => '请选择企业注册类型',
            'type_license.required' => '请选择营业执照类型',
            'pwd_pay.required' => '请填写支付密码',
            'pwd_pay.between' => '支付密码支持6-20位的字母、数字和特殊字符',
            'region_bank.required_if' => '请选择开户行所在地区',
            'brabank_name.required_if' => '请填写支行名称',
            'bank_code.required_if' => '请选择银行',
            'card_no.required_if' => '请填写银行卡号',
            'card_no.numeric' => '银行卡号格式错误',
            'type_explicense.required' => '请选择营业执照有效期类型',
        ];

        if($request->type_explicense == 0)
        {
            $rules['exp_license'] = 'required';
            $message['exp_license.required'] = '请填写营业执照有效期';
        }
        if($request->type_license == 0)
        {
            $rules['org_code'] = 'required';
            $rules['exp_orgcode'] = 'required';
            $message['org_code.required'] = '请填写组织机构代码';
            $message['exp_orgcode.required'] = '请填写组织机构代码有效期';
        }
        $validator = Validator::make($request->all(), $rules, $message);
        $validator->sometimes('card_no', 'numeric', function($input){
            return $input->card_no != '';
        });

        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }
        $resUnitUser = $this->tradeSettingService->openSmsUnitUser($request->all());
        if($resUnitUser['status'])
        {
            if($request->type_register != 1)
            {
                //保存银行卡
                $bankService = new BankService();
                $params = $request->all();
                $shop_id = Auth::user()->shop_id;
                $params['mob_bind'] = Redis::get('lianlianpay:mobile_'.$shop_id);
                $params['city_code'] = $request->region_unit;

                $bankData = [
                    'bank_no' => $params['card_no'],
                    'bank_code' => $params['bank_code'],
                    'city_code' => $params['region_bank'],
                    'bank_account' => '',
                    'brabank_name' => $params['brabank_name'],
                    'bank_mobile' => $params['mob_bind'],
                ];

                $bankService->addBank($bankData);
            }
            return Response::json($resUnitUser);
        }
        return Response::json(['message'=>$resUnitUser['message'],'error'=>$resUnitUser['error']],422);
    }


    /**
     * @name 个体工商户绑卡认证
     * @param Request $request
     * @return mixed
     */
    public function bankcardOpenAuth(Request $request)
    {
        $rules = [
            'card_no' => 'required|regex:/^\d+$/',
            'bind_mob'=>'required|regex:/^1\d{10}$/'
        ];
        $message = [
            'card_no.required'=>'银行卡号必填',
            'card_no.regex' => '银行卡号格式不正确',
            'bind_mob.required'=>'银行绑定手机必填',
            'bind_mob.regex'=>'银行绑定手机格式不正确'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }
        $result = $this->tradeSettingService->bankcardOpenAuth($request->all());
        if($result['status'])
        {
            return Response::json($result);
        }
        return Response::json(['message'=>$result['message']],422);
    }


    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 支付密码验证授权接口 用于更新基本信息
     */
    public function pwdAuth(Request $request)
    {
        $rules = [
            'num_license' => 'required',
            'pwd_pay' => 'required|between:6,20',
        ];
        $message = [
            'num_license.required' => '请填写营业执照号码',
            'pwd_pay.required' => '请填写支付密码',
            'pwd_pay.between' => '支付密码支持6-20位的字母、数字和特殊字符',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $data = [
            'num_license' => $request->num_license,
            'pwd_pay' => $request->pwd_pay
        ];
        $result = $this->tradeSettingService->pwdAuth($data);
        if($result['status'])
        {
            return Response::json($result);
        }
        return Response::json(['message'=>$result['message']],422);
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 修改基本信息
     */
    public function modifyUnitUser(Request $request)
    {
        $rules = [
            'name_user' => 'required',
            'no_idcard' => 'required|id_card',
            'exp_idcard' => 'required|numeric',
            'region_unit' => 'required',
            'addr_unit' => 'required',
            'busi_user' => 'required',
            'name_unit' => 'required',
            'type_license' => 'required',
            'org_code' => 'required_if:type_license,0,1',
            'exp_orgcode' => 'required_if:type_license,0,1',
            'num_license' => 'required',
            'type_explicense' => 'required',
            'exp_license' => 'required_if:type_explicense,0',
            'type_register' => 'required',
        ];
        $message = [
            'name_user.required' => '请填写企业法人',
            'no_idcard.required' => '请填写法人身份证',
            'no_idcard.id_card' => '身份证格式错误',
            'exp_idcard.required' => '请填写法人身份证有效期',
            'exp_idcard.numeric' => '法人身份证有效期只能为数字 如20201212',
            'region_unit.required' => '请选择所在企业所在地',
            'addr_unit.required' => '请填写企业地址',
            'busi_user.required' => '请填写经营范围',
            'name_unit.required' => '请填写企业名称',
            'type_license.required' => '请选择营业执照类型',
            'org_code.required_if' => '表填写组织机构代码',
            'exp_orgcode.required_if' => '请填写组织机构代码有效期',
            'num_license.required' => '请填写营业执照号码',
            'type_explicense.required' => '请选择营业执照有效期类型',
            'exp_license.required_if' => '请填写营业执照有效期',
            'type_register.required' => '请选择企业注册类型',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $resUnitUser = $this->tradeSettingService->modifyUnitUser($request->all());
        if($resUnitUser['status'])
        {
            return Response::json($resUnitUser);
        }
        return Response::json(['message'=>$resUnitUser['message'],'error'=>$resUnitUser['error']],422);
    }


    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 上传照片
     */
    public function uploadUnitPhoto(Request $request)
    {
        $resUnitUser = $this->tradeSettingService->uploadUnitPhoto($request->all());
        if($resUnitUser['status'])
        {
            return Response::json($resUnitUser);
        }
        return Response::json(['message'=>$resUnitUser['message'],'error'=>$resUnitUser['error']],422);
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 对公账户信息修改
     */
    public function modifyUnitUserAcct(Request $request)
    {
        $rules = [
            'region_bank' => 'required',
            'brabank_name' => 'required',
            'bank_code' => 'required',
            'card_no' => 'required|numeric',
        ];
        $message = [
            'region_bank.required' => '请选择开户行所在地区',
            'brabank_name.required' => '请填写支行名称',
            'bank_code.required' => '请选择银行',
            'card_no.required' => '请填写银行卡号',
            'card_no.numeric' => '银行卡号格式错误',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }


        $resUnitUser = $this->tradeSettingService->modifyUnitUserAcct($request->all());
        if($resUnitUser['status'])
        {
            return Response::json($resUnitUser);
        }
        return Response::json(['message'=>$resUnitUser['message']],422);
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 查询子账号信息
     */
    public function getSingleUser()
    {
        $resSingleUser = $this->tradeSettingService->getSingleUser();
        if($resSingleUser['status'])
        {
            return Response::json($resSingleUser['data']);
        }
        return Response::json(['message'=>$resSingleUser['message']],400);
    }
}
