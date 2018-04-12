<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\Shop\ShopService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getCurrentUser()
    {
        $user = Auth::user();
        $shopService = new ShopService();
        $shop = $shopService->getShop($user['shop_id']);
        if($shop) {
            $userShop['id'] = $shop['id'];
            $userShop['name'] = $shop['name'];
            $userShop['logo'] = getImageUrl($shop['logo']);
            $userShop['tel'] = $shop['tel'];
            $userShop['contact'] = $shop['contact'];
            $user['shop'] = $userShop;
        }
        unset($user['password']);
        return $user;
    }

    public function putPassword(Request $request)
    {
        $rules = [
            'password_old'=>'required',
            'password'=>'required|confirmed|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
            'password_confirmation'=>'required|between:6,30'
        ];
        $message = [
            'password_old.required'=>'请填写旧密码',
            'password.required'=>'请填写新密码',
            'password.regex'=>'密码至少6位，且必须包含大写字母、小写字母和数字',
            'password.confirmed'=>'确认密码不匹配'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $userService = new UserService();
        $result = $userService->putPassword($request->password,$request->password_old);
        return Response::json($result);
    }
}
