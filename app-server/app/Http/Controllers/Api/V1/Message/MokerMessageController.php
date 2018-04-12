<?php

namespace App\Http\Controllers\Api\V1\Message;

use App\Http\Controllers\Controller;
use App\Services\Message\MokerMessageService;
use App\Transformers\MokerMessageTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;

class MokerMessageController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/moker/messages.json",
     *   tags={"message"},
     *   summary="获取魔客的消息（分页）",
     *   description="获取魔客的消息（分页）,由于消息是按月分表的，所以结构和其他分页接口有所区别",
     *   operationId="getShopMessages",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="偏移量",
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     description="每页条数",
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="table_suffix",
     *     in="query",
     *     description="下次查询表的后缀",
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功，请求下一页带上当前接口返回的meta里的参数，例如api.moker.com/api/moker/messages.json?offset=2&limit=5&table_suffix=201705, 如果meta里的has_next=false,说明已经没有记录，不需要继续请求了",
     *      @SWG\Property(
     *          example={ "data": { { "id": 3, "moker_id": 1, "type": 2, "content": { "title": "魔客收益", "text": "您邀请的韩信加入魔客，您获得￥1000奖励金！", "name": "韩信", "amount": "￥1000" }, "created_at": "14:35" }, { "id": 2, "moker_id": 1, "type": 1, "content": { "title": "店铺收益", "text": "恭喜！您有一笔金额为￥11.88的交易佣金到账！", "amount": "￥11.88" }, "created_at": "14:31" } }, "meta": { "next_offset": 2, "next_limit": 2, "next_table_suffix": "201711", "has_next": true, "cunread": 0 } }
     *      )
     *   )
     * )
     */
    public function getMokerMessages(MokerMessageService $mokerMessageService)
    {
        $mokerId = Auth::user()->id;
        $offset = Request::get('offset') ? intval(Request::get('offset')) : 0;
        $limit = Request::get('limit') ? intval(Request::get('limit')) : 5;
        $table_suffix = Request::get('table_suffix') ? Request::get('table_suffix') : '';
        $res = $mokerMessageService->getMokerMessages($mokerId, $offset, $limit, new Collection(), $table_suffix);

        //更新未读消息数量
        $key = "moker_message:unread_".$mokerId;
        Redis::connection('db')->del($key);
        $cunread = Redis::connection('db')->get($key);
        $cunread = $cunread && $cunread > 0 ? $cunread : 0;
        if(!$res) {
            $meta['has_next'] = false;
            $meta['cunread'] = $cunread;
            return $this->response->collection(new Collection(), new MokerMessageTransformer())->setMeta($meta);
        }
        $res['meta']['cunread'] = $cunread;
        return $this->response->collection($res['data'], new MokerMessageTransformer())->setMeta($res['meta']);
    }
}
