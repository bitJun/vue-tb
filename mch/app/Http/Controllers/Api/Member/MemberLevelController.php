<?php
/**
 * Created by PhpStorm.
 * User: along
 * Date: 17/9/2
 * Time: 上午11:26
 */
namespace App\Http\Controllers\Api\Member;

use App\Services\Member\MemberLevelService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MemberLevelController extends Controller
{
    protected $memberLevelService = '';

    public function __construct(MemberLevelService $memberLevelService)
    {
        $this->memberLevelService = $memberLevelService;
    }

    public function getMemberLevel()
    {
        $result = $this->memberLevelService->getMemberLevel();
        return Response::json($result);
    }

    public function postMemberLevel(Request $request){
        $rules = [
            'credit_limit'=>'required|numeric|min:0',
            //'keep_limit'=>'numeric|min:0'
        ];
        $message = [
            'credit_limit.required'=>'等级条件必填',
            'credit_limit.numeric'=>'等级条件必须为数字',
            'credit_limit.min'=>'等级条件必须大于0',
            //'keep_limit.required'=>'保留等级的条件必填',
            //'keep_limit.numeric'=>'保留等级的条件必须为数字',
            //'keep_limit.min'=>'保留等级的条件必须大于0'
        ];

        if($request->keep_limit){
            $rules['keep_limit'] = 'numeric|min:0';
            $message = [
                'keep_limit.numeric'=>'保留等级的条件必须为数字',
                'keep_limit.min'=>'保留等级的条件必须大于0'
            ];
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            return Response::json(['message'=>$validator->errors()->first()],422);
        }

        $result = $this->memberLevelService->editMemberLevel($request->all());
        return Response::json($result);
    }

}