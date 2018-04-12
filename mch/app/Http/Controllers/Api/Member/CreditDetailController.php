<?php
/**
 * Created by PhpStorm.
 * User: along
 * Date: 17/9/2
 * Time: 上午11:26
 */
namespace App\Http\Controllers\Api\Member;

use App\Services\Member\CreditDetailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CreditDetailController extends Controller
{
    protected $creditDetailService;
    public function __construct(CreditDetailService $creditDetailService)
    {
        $this->creditDetailService = $creditDetailService;
    }

    public function getDetails(Request $request){
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        if(isset($request->mobile)){
            $params['mobile'] = $request->mobile;
        }
        if(isset($request->member_id)){
            $params['member_id'] = $request->member_id;
        }

        //等级
        if(isset($request->level)){
            $params['level'] = $request->level;
            $params['level'] = $params['level'] ? $params['level'] : 0;
        }
        $response = $this->creditDetailService->getDetails($params);
        return Response::json($response);
    }
}