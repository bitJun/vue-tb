<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/11/29
 * Time: 下午3:30
 */

namespace App\Http\Controllers\Api\V1\MobileSms;

use App\Services\MobileSms\MobileSmsService;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileSmsController extends Controller
{
    use Helpers;

    /**
     * @SWG\POST(path="/send_message.json",
     *   tags={"mobileSms"},
     *   summary="发送短信验证码",
     *   description="发送短信验证码",
     *   operationId="sendSms",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_id",
     *     in="query",
     *     description="设备id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="captcha",
     *     in="query",
     *     description="图片验证码",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          true
     *     }),
     *   )
     * )
     */
    public function sendSms(Request $request,MobileSmsService $mobileSmsService)
    {
        $rules = [
            'mobile' => 'required|digits_between:10,20',
            'captcha' => 'captcha'
        ];
        $message = [
            'mobile.required' => '手机号必须填写',
            'mobile.digits_between' => '手机号格式不正确',
            'captcha.captcha' => '图片验证码不正确，请重新输入'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        //发送短信
        $res = $mobileSmsService->sendMessage($request->all());
        if (!$res['status']) {
            return $this->response->array($res);
        }
        return $this->response->array(['status' => true]);
    }

    /**
     * @SWG\POST(path="/send_reg_message.json",
     *   tags={"mobileSms"},
     *   summary="发送短信验证码 带moker手机号唯一验证",
     *   description="发送短信验证码 带moker手机号唯一验证",
     *   operationId="sendRegSms",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_id",
     *     in="query",
     *     description="设备id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="captcha",
     *     in="query",
     *     description="图片验证码",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          true
     *     }),
     *   )
     * )
     */
    public function sendRegSms(Request $request,MobileSmsService $mobileSmsService)
    {
        $rules = [
            'mobile' => 'required|digits_between:10,20|unique:moker',
            'captcha' => 'captcha'
        ];
        $message = [
            'mobile.required' => '手机号必须填写',
            'mobile.digits_between' => '手机号格式不正确',
            'mobile.unique' => '该手机号已加入魔客，请换一个手机号码',
            'captcha.captcha' => '图片验证码不正确，请重新输入'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        //发送短信
        $res = $mobileSmsService->sendMessage($request->all());
        if (!$res['status']) {
            return $this->response->array($res);
        }
        return $this->response->array(['status' => true]);
    }
}