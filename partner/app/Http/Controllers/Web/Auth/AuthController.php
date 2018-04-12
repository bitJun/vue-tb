<?php

namespace App\Http\Controllers\Web\Auth;

use App\Services\Shop\ShopService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $input = [
            'mobile' => $request->get('mobile'),
            'password' => $request->get('password'),
            'captcha'  => $request->get('captcha')
        ];
        $rules = [
            'mobile'  =>  'required',
            'password'  =>  'required',
            'captcha' => 'required|captcha'
        ];
        $message = [
            'mobile.required'=>'请输入手机号',
            'password.required'=>'请输入密码',
            'captcha.required'=>'请输入验证码',
            'captcha.captcha'=>'验证码不正确'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()) {
            $this->clearCaptcha();
            return response()->json($validator->getMessageBag()->toArray(), 422);
        }

        $credentials = $request->only('mobile', 'password');
        try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    $this->clearCaptcha();
                    return response()->json(['error' => '手机号或者密码错误!'], 401);
                }
        } catch (JWTException $e) {
            $this->clearCaptcha();
            return response()->json(['error' => '无法创建token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        $this->clearCaptcha();
    }

    public function clearCaptcha()
    {
        Session::forget('captcha');
    }
}
