<?php

namespace App\Http\Controllers\Api\Credit;

use App\Services\Credit\CreditService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CreditRuleController extends Controller
{
    public function __construct(CreditService $creditService)
    {
        $this->creditService = $creditService;
    }

    public function putCreditRule(Request $request)
    {
        $rules = [
            'self_percent'=>'required|numeric|min:0',
            'parent_percent'=>'required|numeric|min:0',
            'partner_percent'=>'required|numeric|min:0'
        ];
        $message = [
            'self_percent.required'=>'请填写消费获得魔豆比例',
            'self_percent.numeric'=>'消费获得魔豆比例必须为数字',
            'self_percent.min'=>'消费获得魔豆比例必须大于0',
            'parent_percent.required'=>'请填写消费上级获得魔豆比例',
            'parent_percent.numeric'=>'消费上级获得魔豆比例必须为数字',
            'parent_percent.min'=>'消费上级获得魔豆比例必须大于0',
            'partner_percent.required'=>'请填写消费股东合伙人获得魔豆比例',
            'partner_percent.numeric'=>'股东合伙人消费获得魔豆比例必须为数字',
            'partner_percent.min'=>'股东合伙人消费获得魔豆比例必须大于0',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->creditService->putCreditRule($request->all());
        return Response::json($result);
    }

    public function getCreditRule()
    {
        $result = $this->creditService->getCreditRule();
        return Response::json($result);
    }
}
