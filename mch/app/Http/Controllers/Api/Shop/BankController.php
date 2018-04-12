<?php

namespace App\Http\Controllers\Api\Shop;

use App\Services\Shop\BankService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     *
     *
     */
    public function getBanks()
    {
        $data = $this->bankService->getBanks();
        return Response::json($data);
    }

    public function getBankById($id)
    {
        $data = $this->bankService->getBankById($id);
        return Response::json($data);
    }

    public function postBank(Request $request)
    {
        $rules = [
            //'bank_type'=>'required',
            'bank_code'=>'required',
            'bank_no'=>'required|regex:/^\d+$/',
            'bank_mobile'=>'required',
            'bank_account'=>'required',
            'brabank_name'=>'required'
        ];
        $message = [
            //'bank_type.required'=>'请选择账户类型',
            'bank_code.required'=>'请选择开户银行',
            'bank_no.required'=>'请填写银行卡号',
            'bank_no.regex'=>'银行卡号格式不正确',
            'bank_mobile.required'=>'请填写银行预留手机号',
            'bank_account.required'=>'请填写开户人姓名',
            'brabank_name.required'=>'请填写开户运行完整名称'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->bankService->postBank($request->all());
        if(!$result['status'])
        {
            return Response::json(['message'=>$result['msg']],422);
        }
        return Response::json($result);
    }

    public function putBank(Request $request,$id)
    {
        $rules = [
            //'bank_type'=>'required',
            'bank_code'=>'required',
            'bank_no'=>'required|regex:/^\d+$/',
            'bank_mobile'=>'required',
            'bank_account'=>'required',
            'brabank_name'=>'required'
        ];
        $message = [
            //'bank_type.required'=>'请选择账户类型',
            'bank_code.required'=>'请选择开户银行',
            'bank_no.required'=>'请填写银行卡号',
            'bank_no.regex'=>'银行卡号格式不正确',
            'bank_mobile.required'=>'请填写银行预留手机号',
            'bank_account.required'=>'请填写开户人姓名',
            'brabank_name.required'=>'请填写开户运行完整名称'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->bankService->putBank($request->all(),$id);
        if(!$result['status'])
        {
            return Response::json(['message'=>$result['msg']],422);
        }
        return Response::json($result);
    }

    public function deleteBank($id)
    {
        $data = $this->bankService->deleteBank($id);
        return Response::json($data);
    }

    /**
     * @name 个体工商户银行卡绑卡验证
     * @param Request $request
     * @return mixed
     */
    public function bankcardAuthVerfy(Request $request){

        $rules = [
            'verify_code'=>'required|numeric'
        ];
        $message = [
            'verify_code.required'=>'请填写验证码',
            'verify_code.numeric'=>'验证码格式不正确'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $data = [
            'verify_code' => $request->verify_code,
            'bank_id'     => $request->id,
            'bank_mobile' => $request->bank_mobile

        ];
        $result = $this->bankService->bankcardAuthVerfy($data);
        if($result['status'])
        {
            return Response::json($result);
        }
        return Response::json(['message'=>$result['message'],'result'=>$result],422);

    }
}
