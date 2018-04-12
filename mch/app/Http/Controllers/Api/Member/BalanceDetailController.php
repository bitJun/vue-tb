<?php
/**
 * Created by PhpStorm.
 * User: along
 * Date: 17/9/2
 * Time: 上午11:26
 */
namespace App\Http\Controllers\Api\Member;

use App\Services\Member\BalanceDetailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BalanceDetailController extends Controller
{
    protected $balanceDetailService;
    public function __construct(BalanceDetailService $balanceDetailService)
    {
        $this->balanceDetailService = $balanceDetailService;
    }

    public function getDetails(Request $request){
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['mobile'] = isset($request->mobile) ? $request->mobile : '';

        if(isset($request->member_id)){
            $params['member_id'] = $request->member_id;
        }

        //等级
        if(isset($request->level)){
            $params['level'] = $request->level;
            $params['level'] = $params['level'] ? $params['level'] : 0;
        }
        $response = $this->balanceDetailService->getDetails($params);
        return Response::json($response);
    }
}