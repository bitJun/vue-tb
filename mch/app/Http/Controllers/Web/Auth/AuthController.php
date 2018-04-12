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
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'captcha'  => $request->get('captcha')
        ];
        $rules = [
            'username'  =>  'required',
            'password'  =>  'required',
            'captcha' => 'required|captcha'
        ];
        $message = [
            'username.required'=>'请输入用户名或者手机号',
            'password.required'=>'请输入密码',
            'captcha.required'=>'请输入验证码',
            'captcha.captcha'=>'验证码不正确'
        ];
        //$this->validate($request, $rules, $message);
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()) {
            $this->clearCaptcha();
            return response()->json($validator->getMessageBag()->toArray(), 422);
        }

        $credentials = $request->only('username', 'password');
        $credentialsMobile = ['mobile'=>$input['username'], 'password'=>$input['password']];
        try {
            if (! $token = JWTAuth::attempt($credentialsMobile)) {
                if (! $token = JWTAuth::attempt($credentials)) {
                    $this->clearCaptcha();
                    return response()->json(['error' => '用户名|手机号或者密码错误!'], 401);
                }
            }
/*            $user = Auth::user();
            $shopService = new ShopService();
            $shop = $shopService->getShop($user['shop_id']);
            if($shop) {
                $userShop['id'] = $shop['id'];
                $userShop['name'] = $shop['name'];
                $userShop['logo'] = $shop['logo'];
                $userShop['tel'] = $shop['tel'];
                $userShop['contact'] = $shop['contact'];
                $user['shop'] = $userShop;
            }*/
        } catch (JWTException $e) {
            $this->clearCaptcha();
            return response()->json(['error' => '无法创建token'], 500);
        }

        return response()->json(compact('token', 'user'));
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
