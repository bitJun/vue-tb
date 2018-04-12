<?php
namespace App\Http\Controllers\Api\V1\Order;

use App\Services\Moker\MokerOrderService;
use App\Services\Moker\MokerService;
use App\Services\Order\OrderService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    use Helpers;

    /**
     * @SWG\Post(path="/order.json",
     *   tags={"order"},
     *   summary="开启魔客下单",
     *   description="开启魔客下单",
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
     *     name="amount",
     *     in="query",
     *     description="付款金额",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="level_id",
     *     in="query",
     *     description="等级ID",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="pay_code",
     *     in="query",
     *     description="支付方式, alipay 、 wxpay",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={"status":true,"html":"app_id=2017070807680397&biz_content=%7B%22timeout_express%22%3A%2230m%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%2C%22total_amount%22%3A%221.00%22%2C%22subject%22%3A%22%5Cu5f00%5Cu542f%5Cu9b54%5Cu5ba2%22%2C%22out_trade_no%22%3A%22201711301512040913107844%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&notify_url=http%3A%2F%2Fapimd.taotui8.com%2Fapi%2Fpaynotify%2F2%2F201711301512040913107844&sign_type=RSA2&timestamp=2017-11-30+19%3A21%3A53&version=1.0&sign=A4nXy4wIF7i8G%2BgT4wY5HRFLxYdbNdcyW3LmuHOCiYP055KXqnt%2FWxaID6qvYz0qQLMaOO4GgVG4J22LajBsnKirl5j0unK%2BI85hntCbm5obVFbsCyEDDhH5oK%2Fvz7FBoK7mQdSegjxrfa91Pbtex5UQguF1Wfq%2BRsIMbX%2BVyU7bGGx%2FCTClQ98hD4Me5uQdtyMfV%2FOQtsloN0zHoRij2nHNI5jG1cW6FYMZ6ohwuJbAGBLAYHH%2F5o9Onv1bmdG7O%2BD32Lzd10%2B%2BSvJguRGaftVwOJa8qkW7niWJc%2FdIiCyNWbRqnKG1%2FBn9Qyf%2Bb%2F1wrywTf507bjMTmMTWe0Nndg%3D%3D"}),
     *   )
     * )
     */
    public function createOrder(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'pay_code' => 'required',
            'level_id' => 'required'
        ];
        $message = [
            'amount.required' => '请输入金额',
            'pay_code.required' => '请选择支付方式',
            'level_id.required' => '请选择等级',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }
        $param = $request->all();
        $moker_id = Auth::user()->id;
        $shop_id = MODIAN;

        $mokerService = new MokerService();
        $this_moker = $mokerService->getById($moker_id);
        if($this_moker['level_id'] == $param['level_id']){
            $this->response->errorBadRequest('不能重复升级');
        }

        $orderService = new OrderService($shop_id);
        $order_sn = $orderService->get_order_sn();
        $dbRedis = Redis::connection('db');
        $incNum = $dbRedis->get('increment:order_id');
        if (!$incNum) {
            $incNum = $dbRedis->incrBy('increment:order_id', 1);
        }
        $moker['order_id'] = $incNum;
        $moker['moker_id'] = $moker_id;
        $moker['level_id'] = $param['level_id'];
        $mo_service = new MokerOrderService();
        $moker_order = $mo_service->create($moker);
        if(!$moker_order){
            $this->response->errorBadRequest('魔客开启失败');
        }

        $data['id'] = $incNum;
        $data['member_id'] = 0;
        $data['moker_id'] = $moker_id;
        $data['nickname'] = Auth::user()->name;
        $data['mobile'] = Auth::user()->mobile;
        $data['order_sn'] = $order_sn;
        $data['shop_id'] = $shop_id;
        $data['title'] = '开启魔客';
        $data['origin_amount'] = $param['amount'];
        $data['amount'] = $param['amount'];
        $data['type'] = ORDER_TYPE_INVITE_MOKER;
        $data['status'] = ORDER_TOPAY;
        $order = $orderService->create($data);
        if ($order) {
            $dbRedis->incrBy('increment:order_id', 1);
        }
        $pay_code=isset($param['pay_code']) ? $param['pay_code'] : 'llpay';
        $pay_type=isset($param['pay_type']) ? $param['pay_type'] : 'Y';

        $form_params = array('shop_id' => $shop_id, 'order_id' => $order['id'], 'pay_code' => $pay_code, 'pay_type' => $pay_type);
        $client = new Client(['base_uri' => env('APP_API_URL'),'timeout' => 200,'verify' => false]);
        $_response = $client->post('api/directpay.json',['form_params' => $form_params,'debug' => false])->getBody()->getContents();
        $response = json_decode($_response,true);
        $response = $response['response'];
        if($pay_code == 'alipay'){
            return $this->response->array(['status' => true, 'order_id' => $incNum ,'html' => $response]);
        }
        if(isset($response['status']) && $response['status'] == 1){
            $html = isset($response['status']) && $response['status'] == 1 ? $response['html'] : '';
            return $this->response->array(['status' => true, 'code' => $pay_code, 'html' => $html]);
        }
        $msg = isset($response['ret_msg']) ? $response['ret_msg'] : '系统抖动';
        $this->response->errorInternal($msg);
    }

    /**
     * @SWG\Get(path="/order/2367.json",
     *   tags={"order"},
     *   summary="支付结果页订单详情",
     *   description="支付结果页订单详情",
     *   operationId="edit",
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
     *      @SWG\Property(example={"id":2367,"shop_id":8,"member_id":0,"moker_id":3,"nickname":"\u9633","username":null,"mobile":"13660823426","title":"\u5f00\u542f\u9b54\u5ba2","origin_amount":"0.01","amount":"0.01","status":40,"type":2,"order_sn":"E201712060338161571258","order_type":1,"code":"alipay","code_name":"\u652f\u4ed8\u5b9d","payment_sn":"201712061512545896177169","trade_sn":"2017120621001004460546036827","credit_rule":null,"memo":null,"is_settled":1,"finished_at":null,"created_at":"2017-12-06 15:38:16","updated_at":"2017-12-06 15:38:24","deleted_at":null}),
     *   )
     * )
     */
    public function getOrder($id){
        $shop_id = MODIAN;
        $orderService = new OrderService($shop_id);
        $order = $orderService->getOrder($id);
        return $this->response->array($order);
    }

}