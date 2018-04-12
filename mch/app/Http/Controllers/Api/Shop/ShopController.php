<?php

namespace App\Http\Controllers\Api\Shop;

use App\Services\Shop\ShopService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function putShop(Request $request)
    {
        $rules = [
            'tel'=>'required|digits_between:6,20',
            'province'=>'required',
            'city'=>'required',
            'district'=>'required',
            'address'=>'required',
        ];
        $message = [
            'tel.required'=>'联系方式必须填写',
            'province.required'=>'所属省份必须选择',
            'city.required'=>'所在城市必须选择',
            'tel.digits_between'=>'联系方式格式不正确',
            'district.required'=>'所在区必须选择',
            'address.required'=>'详细地址必须填写',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->shopService->putShop($request->all());
        return Response::json($result);
    }

    public function getShop()
    {
        $result = $this->shopService->getShop(Auth::user()->shop_id);
        return Response::json($result);
    }
}
