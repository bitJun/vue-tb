<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Services\Member\MemberService;
use App\Services\Member\ShopMemberService;
use App\Services\MobileSms\MobileSmsService;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function bindMember(Request $request){
        $rules = [
            'mobile'=>'required|digits_between:6,20',
            'order_id'=>'required',
            'shop_id' => 'required'
        ];
        $message = [
            'mobile.required'=>'手机号码不能为空',
            'mobile.digits_between'=>'手机号码格式不正确',
            'order_id'=>'订单id不能为空',
            'shop_id' => '店铺id不能为空'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(array('message' => $validator->errors()->first()),400);
        }

        $params['mobile'] = $request->get('mobile');
        $params['verify_code'] = $request->get('verify_code') ? $request->get('verify_code') : 0;

        //验证码是否正确
        $mobileSmsService = new MobileSmsService();
        if(!$mobileSmsService->checkVerifyCode($params['verify_code'],$params['mobile'])){
            return Response::json(array('message'=>'验证码错误'),400);
        }
        $order_id = $request->get('order_id');
        $shop_id = $request->get('shop_id');
        $orderService = new OrderService($shop_id);
        $order = $orderService->getOrder($order_id);
        if(!$order){
            return Response::json(array('message' => '订单数据有误'),400);
        }

        $memberService = new MemberService();
        $result = $memberService->putMember($order['member_id'],['mobile'=>$params['mobile']]);
        if($result){
            //更新订单表的mobile
            $orderService->updateOrder(['mobile'=>$params['mobile']],$order_id);
            return Response::json(['msg'=>'绑定成功']);
        }
        return Response::json(['msg'=>'绑定失败']);

    }
}
