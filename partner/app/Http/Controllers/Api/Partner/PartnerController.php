<?php

namespace App\Http\Controllers\Api\Partner;

use App\Services\Partner\PartnerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PartnerController extends Controller
{
    protected $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        unset($user['password']);
        return $user;
    }

    public function getPartners(Request $request)
    {
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['mobile'] = isset($request->mobile) ? $request->mobile : '';
        $params['name'] = isset($request->name) ? $request->name : '';

        $response = $this->partnerService->getPartners($params);
        return Response::json($response);
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

        $partnerService = new PartnerService();
        $result = $partnerService->putPassword($request->password,$request->password_old);
        return Response::json($result);
    }
}
