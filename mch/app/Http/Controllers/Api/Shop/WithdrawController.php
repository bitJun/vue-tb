<?php

namespace App\Http\Controllers\Api\Shop;

use App\Services\Shop\WithdrawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    protected $withdrawService = '';
    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    public function getWithdraws(Request $request){
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['status'] = isset($request->status) ? $request->status : 0;

        //时间
        $params['date_start'] = isset($request->date_start) ? $request->date_start : '';
        $params['date_end'] = isset($request->date_end) ? $request->date_end : '';

        $response = $this->withdrawService->getWithdraws($params);
        return Response::json($response);
    }

    //提现到银行卡
    public function postWithdraw(Request $request)
    {
        $rules = [
            'bank_id'=>'required|regex:/^\d+$/',
            'amount'=>'required'
        ];
        $message = [
            'bank_id.required'=>'请选择一张银行卡',
            'bank_id.regex'=>'银行卡参数有误',
            'amount.required'=>'提现金额必须大于1元'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->withdrawService->postWithdraw($request->all());
        if(!$result['status'])
        {
            return Response::json(['message'=>$result['msg']],422);
        }
        return Response::json($result);
    }

    public function getWithdrawStatus(){
        $response = $this->withdrawService->getWithdrawStatus();
        return Response::json($response);
    }
}
