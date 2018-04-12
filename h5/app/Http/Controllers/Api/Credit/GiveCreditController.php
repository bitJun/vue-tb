<?php

namespace App\Http\Controllers\Api\Credit;

use App\Http\Controllers\Controller;
use App\Services\Credit\GiveCreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GiveCreditController extends Controller
{
    public function giveCredit(Request $request){
        $rules = [
            'mobile'=>'required|digits_between:6,20',
            //'captcha' => 'captcha'
        ];
        $message = [
            'mobile.required'=>'手机号码必须填写',
            'mobile.digits_between'=>'手机号码格式不正确',
            //'captcha.captcha'=>'图片验证码不正确，请重新输入'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(array('message' => $validator->errors()->first()),400);
        }

        $params['mobile'] = $request->get('mobile');
        $params['member_id'] = $request->get('mid') ? $request->get('mid') : 0;
        $params['order_id'] = $request->get('order_id') ? $request->get('order_id') : 0;
        $params['shop_id'] = $request->get('shop_id') ? $request->get('shop_id') : 0;
        //$params['verify_code'] = $request->get('verify_code') ? $request->get('verify_code') : 0;


        $giveCreditService = new GiveCreditService();
        $result = $giveCreditService->giveCredit($params);

        return $result;

    }
}
