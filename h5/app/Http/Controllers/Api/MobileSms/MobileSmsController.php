<?php

namespace App\Http\Controllers\Api\MobileSms;

use App\Http\Controllers\Controller;
use App\Services\Member\MemberService;
use App\Services\MobileSms\MobileSmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MobileSmsController extends Controller
{
    public function sendVerifyCode(Request $request)
    {
        $rules = [
            'mobile'=>'required|digits_between:6,20',
            'captcha' => 'captcha'
        ];
        $message = [
            'mobile.required'=>'手机号码必须填写',
            'mobile.digits_between'=>'手机号码格式不正确',
            'captcha.captcha'=>'图片验证码不正确，请重新输入'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(array('message' => $validator->errors()->first()),400);
        }

        $mobile = $request->get('mobile');
        $data = $request->all();
        $memberId = isset($data['mid']) ? $data['mid'] : '';
        $captcha = isset($data['captcha']) ? $data['captcha'] : '';

        //判断要不要显示图片验证码，防刷
        $cacheKey = 'h5csms:'.$memberId.$mobile;
        if(!$captcha && Cache::get($cacheKey))
        {
            Cache::increment($cacheKey);
            return Response::json(['captcha'=>1]);
        }else{
            Cache::put($cacheKey, 1, Carbon::now()->addMinutes(60));
        }

        $mobileSmsService = new MobileSmsService();
        $mobileSmsService->sendVerifySms($mobile);
        return Response::json(array('message' => '发送成功'));
    }
}
