<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Jobs\PushMsg;
use App\Services\Moker\MokerService;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndexController extends Controller {

    use Helpers;

    /**
     * @SWG\Get(path="/auth/statistics.json",
     *   tags={"auth"},
     *   summary="首页统计",
     *   description="首页统计",
     *   operationId="getCreditRule",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjksImlzcyI6Imh0dHA6Ly93d3cubW9kaWFuLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNDk3OTYyODUwLCJleHAiOjE2NTU2NDI4NTAsIm5iZiI6MTQ5Nzk2Mjg1MCwianRpIjoic2dqQUFPekNudklUcHVnYiJ9.LX5Ylzpo8e9_xYFti0fIJXr_dYKqGkm_HQjnOX7RI9E"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功（today_profit：今日收益；all_profit：累积收益；balance:可提金额；my_team：魔客团队；team_profit：团队累积收益；my_shop：我的魔店；shop_comm：店铺累积收益 ；is_payment：是否魔客付款；is_addshop：是否添加店铺；is_addaccount：是否添加提现账户；iOSBadge：未读消息数）",
     *      @SWG\Property(
     *          example=
      {
      "data": {
      "today_profit": "1000.54",
      "all_profit": "1000.54",
      "balance": "1000.54",
      "my_team": 1,
      "team_profit": 1000.9,
      "my_shop": 1,
      "shop_comm": "0.54",
      "is_payment": 1,
      "is_addshop": 1,
      "is_addaccount": 1
      }
      }
     *      )
     *   )
     * )
     */
    public function getStatistics(MokerService $MokerService) {
        $Data['moker_id'] = Auth::user()->id;
        $res = $MokerService->getStatistics($Data);
        return $res;
    }

}
