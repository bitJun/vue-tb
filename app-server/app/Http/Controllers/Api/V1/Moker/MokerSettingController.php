<?php

namespace App\Http\Controllers\Api\V1\Moker;

use App\Http\Controllers\Controller;
use App\Services\Moker\MokerSettingService;
use App\Transformers\MokerSettingTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class MokerSettingController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/moker/setting.json",
     *   tags={"moker"},
     *   summary="获取当前登录魔客的设置信息",
     *   description="获取当前登录魔客的设置信息",
     *   operationId="getSetting",
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
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"data":{"msg_setting":{"system":1,"shop_comm":0,"moker_comm":1,"moker_join":0}}}
     *      )
     *   )
     * )
     */
    public function getSetting(MokerSettingService $mokerSettingService)
    {
        $setting = $mokerSettingService->getSetting(Auth::user()->id);
        return $this->response->item($setting, new MokerSettingTransformer())->setStatusCode(200);
    }


    /**
     * @SWG\Post(path="/moker/setting.json",
     *   tags={"moker"},
     *   summary="当前登录魔客保存相关设置",
     *   description="当前登录魔客保存相关设置",
     *   operationId="setSetting",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="msg_setting[system]",
     *      description="推送设置->系统通知, 1:开启, 0:关闭",
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="msg_setting[shop_comm]",
     *      description="推送设置->店铺收益, 1:开启, 0:关闭",
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="msg_setting[moker_comm]",
     *      description="推送设置->魔客收益, 1:开启, 0:关闭",
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="msg_setting[moker_join]",
     *      description="推送设置->魔客加入, 1:开启, 0:关闭",
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"data":{"msg_setting":{"system":1,"shop_comm":0,"moker_comm":1,"moker_join":0}}}
     *      )
     *   )
     * )
     */
    public function setSetting(MokerSettingService $mokerSettingService)
    {
        $data = Request::all();
        $setting = $mokerSettingService->setSetting(Auth::user()->id, $data);
        return $this->response->item($setting, new MokerSettingTransformer())->setStatusCode(200);
    }
}
