<?php

namespace App\Http\Controllers\Api\V1\Moker;

use App\Services\Moker\MokerLevelService;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class MokerLevelController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/moker_level.json",
     *   tags={"moker"},
     *   summary="获取魔客等级接口",
     *   description="开启魔客付款页面,获取等级信息",
     *   operationId="get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功,等级id;加盟费price;可获得加盟店铺流水的佣金百分比comm_percent;奖励金parent_comm",
     *      @SWG\Schema(example={"id":2,"name":"\u9b54\u5ba2","priv":{"limit_shop":10,"limit_inviter":0},"price":"1980.00","comm_percent":"0.20","parent_comm":"1000.00","created_at":"2017-11-22 17:53:20","updated_at":"2017-11-22 17:53:23"})
     *   )
     * )
     */
    public function getMokerLevel(MokerLevelService $mokerLevelService)
    {
        return $mokerLevelService->getMokerLevel();
    }


}
