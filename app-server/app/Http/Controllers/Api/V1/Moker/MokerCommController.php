<?php

namespace App\Http\Controllers\Api\V1\Moker;

use App\Http\Controllers\Controller;
use App\Model\Shop;
use App\Services\Moker\MokerCommService;
use App\Services\Moker\MokerService;
use App\Transformers\MokerCommTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MokerCommController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/moker/commission.json",
     *   tags={"moker"},
     *   summary="收益列表",
     *   description="收益列表",
     *   operationId="getCommission",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLm1va2VyLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNTExOTI0NDIwLCJuYmYiOjE1MTE5MjQ0MjAsImp0aSI6ImNTeVBSbFBHOW1BYm05MkkiLCJzdWIiOjEsInBydiI6Ijg0Mzg4YTkzOWE3OGE1ZDlmYzVlNTE3NmM2MjM5Y2U0NGFkZTZmZTcifQ.CJcZqy0BY8leArhFCsW5X8LUPaB3mqtNpnRmcNWH7LQ"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      description="类型 0:店铺交易佣金 1:魔客邀请奖励",
     *      required=false,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="source",
     *      description="类型 today:今日 all:累计",
     *      required=false,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="limit",
     *      description="每页数量 默认10",
     *      required=false,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"data": {
                            {
                                "comm": "1000.00",
                                "invitee_moker_id": 2,
                                "title": "邀请魔客奖金",
                                "logo": "",
                                "shop_id": 0,
                                "created_at": "1970-01-01  08:00:00"
                            },
                            {
                                "comm": "0.54",
                                "invitee_moker_id": 0,
                                "title": "魔店示范店交易佣金",
                                "logo": "http://oth9z8cjj.bkt.clouddn.com/2017/11/13/FkzIdklEPJDPe9mBGf3PS2hEMrq6.jpg",
                                "shop_id": 1,
                                "created_at": "1970-01-01  08:00:00"
                            }
                        },
                        "meta": {
                            "commSum": "1000.54",
                            "pagination": {
                            "total": 2,
                            "count": 2,
                            "per_page": 10,
                            "current_page": 1,
                            "total_pages": 1,
                            "links": ""
                        }
                    }
                }
     *      )
     *   )
     * )
     */
    public function getCommission(Request $request,MokerCommService $mokerCommService)
    {
        $params = [];
        //类型
        if(isset($request->type)){
            $params['type'] = $request->type;
        }
        //按今日取
        $params['source'] = (isset($request->source) && $request->source=='today') ? 'today' :'all';
        $params['moker_id'] = Auth::user()->id;
        $params['limit'] = isset($request->limit) ? $request->limit : 10;

        $data = $mokerCommService->getCommission($params);

        $commSum = 0.00;
        if ($data) {
            $mokerService = new MokerService();
            $shopModel = new Shop();
            foreach ($data as &$_data){
                switch ($_data['type']){
                    case 1://魔客邀请
                        //邀请人
                        $invitte_moker = $mokerService->getById($_data['invitee_moker_id']);
                        $_data['title'] = '邀请魔客奖金';
                        $_data['logo'] = $invitte_moker['avatar'];
                        break;
                    default :
                        //交易佣金
                        $shop = $shopModel->where('id',$_data['shop_id'])->first();
                        $_data['title'] = $shop['name'].'交易佣金';
                        $_data['logo'] = $shop['logo'];
                        break;
                }

            }

            $commSum = $mokerCommService->getCommSum($params);
        }

        return $this->response->paginator($data, new MokerCommTransformer())->addMeta('commSum',$commSum);
    }

    /**
     * @SWG\Get(path="/ranking/{type}.json",
     *   tags={"moker"},
     *   summary="富豪榜",
     *   description="富豪榜",
     *   operationId="edit",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="type",
     *     in="path",
     *     description="获取类型, all所有、month月、week周",
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功;amount 当前累计收益, in 0未上榜1上榜, data排行列表",
     *      @SWG\Property(example={"amount":"3000.90","in":1,"data":{{"moker_id":1,"amount":"3000.90","name":"\u674e\u767d","avatar":null},{"moker_id":4,"amount":"14.18","name":"\u9ece\u4e1c","avatar":"2017\/12\/01\/FrdAMu1jfHC693hLNCoKu5GEqtl_.jpg"},{"moker_id":3,"amount":"7.18","name":"\u9633","avatar":"2017\/12\/01\/Fkm3NSlTs4atftUhbjkkY-fkR3JC.jpg"},{"moker_id":2,"amount":"0.72","name":"\u674e\u5927\u767d","avatar":null}}}),
     *   )
     * )
     */
    public function ranking_list($type,MokerCommService $mokerCommService){
        $data = $mokerCommService->ranking_list($type,Auth::user()->id);
        return $this->response->array($data);
    }
}