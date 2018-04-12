<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/11/24
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Model\Shop;
use App\Model\ShopApply;
use App\Services\Shop\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function postShop(Request $request,ShopService $shopService)
    {
        $rules = [
            'company' => 'required',
            'logo' => 'required',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'district' => 'required|integer',
            'address' => 'required',
            'tag_id'=>'required|integer',
            'num_license' => 'required',
            'contact' => 'required',
            'mobile' => 'required|digits_between:6,20'
        ];
        $message = [
            'company.required'=>'请填写名称',
            'logo.required'=>'请上传LOGO',
            'province.required'=>'请选择省',
            'province.integer'=>'省类型不正确',
            'city.required'=>'请选择市',
            'city.integer'=>'市类型不正确',
            'district.required'=>'请选择区',
            'district.integer'=>'区类型不正确',
            'address.required'=>'请填写详细地址',
            'tag_id.required'=>'请选择行业类型',
            'tag_id.integer'=>'行业类型只支持正整数类型',
            'num_license.required'=>'请上传营业执照',
            'contact.required'=>'请填写联系人',
            'mobile.required'=>'请填写手机号码',
            'mobile.digits_between'=>'手机号码格式不正确',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json($validator->errors()->first(),422);
        }
        $shopService->postShop($request->all());
        return Response::json(['status'=>true]);
    }

    public function getShops(Request $request,ShopService $shopService){
        $params = [];
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['name'] = isset($request->name) ? $request->name : '';
        $params['tel'] = isset($request->tel) ? $request->tel : '';
        $params['contact'] = isset($request->contact) ? $request->contact : '';

        $data = $shopService->getPartnerShops($params);
        return Response::json($data);

    }

    public function getShop($id,ShopService $shopService)
    {
        $data = $shopService->getShop($id);
        return Response::json($data);
    }

    public function putShop($id,Request $request,ShopService $shopService)
    {
        $rules = [
            'name' => 'required',
            'logo' => 'required',
            'address' => 'required',
            'tag_id'=>'required|integer',
            'contact' => 'required',
            'tel' => 'required|digits_between:6,20'
        ];
        $message = [
            'name.required'=>'请填写名称',
            'logo.required'=>'请上传LOGO',
            'address.required'=>'请填写详细地址',
            'tag_id.required'=>'请选择行业类型',
            'tag_id.integer'=>'行业类型只支持正整数类型',
            'contact.required'=>'请填写联系人',
            'tel.required'=>'请填写联系电话',
            'tel.digits_between'=>'联系电话格式不正确',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json($validator->errors()->first(),422);
        }
        $result = $shopService->putShop($id,$request->all());
        if(!$result['status'])
        {
            return Response::json($result['msg'],422);
        }
        return Response::json(['status'=>true]);
    }
}