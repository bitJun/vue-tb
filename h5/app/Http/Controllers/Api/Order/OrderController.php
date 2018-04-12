<?php
/**
 * Created by PhpStorm.
 * User: beller
 * Date: 2017/10/24
 * Time: 下午2:47
 */

namespace App\Http\Controllers\Api\Order;


use App\Services\Commission\CommissionService;
use App\Services\Member\MemberBindService;
use App\Services\Member\MemberService;
use App\Services\Order\OrderService;
use App\Services\Shop\ShopService;
use App\Services\User\UserService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController
{

    public function createOrder() {
        $rules = [
            'shop_id' => 'required',
            'amount' => 'required',
            //'pay_code' => 'required'
        ];
        $message = [
            'shop_id.required' => '未知店铺',
            'amount.required' => '请输入金额',
            //'originAmount.regex'=>'价格格式不正确',
        ];
        $validator = Validator::make(Request::all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('status' => false, 'message' => '参数不合法'),400);
        }
        $param = Request::all();
        $shop_id = isset($param['shop_id']) && $param['shop_id'] ? $param['shop_id'] : 0;
        if (!$shop_id)
            return [];

        $user_id = isset($param['user_id']) && $param['user_id'] ? $param['user_id'] : 0;
        $memo = isset($param['memo']) && $param['memo'] ? $param['memo'] : '';

        $shopService = new ShopService();
        $shop = $shopService->getShop($shop_id);
        if (!$shop)
            return [];

        $user = session('wechat.oauth_user');
        $unionid = '';
        $openid = '';
        if($user) {
            $openid = isset($user['id']) ? $user['id'] : '';
            $org = $user->getOriginal();
            if($org && isset($org['unionid'])) {
                $unionid = $org['unionid'];
            }
        }
        $member = $this->createMember($unionid);
        $orderService = new OrderService($shop_id);
        $order_sn = $orderService->get_order_sn();
        $dbRedis = Redis::connection('db');
        $incNum = $dbRedis->get('increment:order_id');
        if (!$incNum) {
            $incNum = $dbRedis->incrBy('increment:order_id', 1);
        }

        if($user_id){
            $userServie = new UserService();
            $user = $userServie->getUser($user_id,$shop_id);
            if ($user){
                $data['username'] = $user['name'];
            }

        }

        $data['id'] = $incNum;
        $data['member_id'] = $member['id'];
        $data['nickname'] = $member['nickname'];
        $data['mobile'] = $member['mobile'];
        $data['order_sn'] = $order_sn;
        $title = $shop['name'] . '收银';
        $data['shop_id'] = $shop_id;
        $data['title'] = $title;
        $data['origin_amount'] = $param['amount'];
        $data['amount'] = $param['amount'];
        $data['type'] = 0;
        $data['status'] = ORDER_TOPAY;
        if ($memo){
            $data['memo'] = $memo;
        }
        $order = $orderService->create($data);
        if ($order) {
            $dbRedis->incrBy('increment:order_id', 1);
        }
        $pay_code=isset($param['pay_code']) ? $param['pay_code'] : 'llpay';
        $pay_type=isset($param['pay_type']) ? $param['pay_type'] : 'W';

        $form_params = array('shop_id' => $shop_id, 'order_id' => $order['id'], 'pay_code' => $pay_code, 'pay_type' => $pay_type, 'openid' => $openid);
        $client = new Client(['base_uri' => env('APP_API_URL'),'timeout' => 200,'verify' => false]);
        $_response = $client->post('api/directpay.json',['form_params' => $form_params,'debug' => false])->getBody()->getContents();
        $response = json_decode($_response,true);
        $response = $response['response'];
        if(isset($response['status']) && $response['status'] == 1){
            $html = isset($response['status']) && $response['status'] == 1 ? $response['html'] : '';
            return Response::json(array('status' => true, 'code' => $pay_code, 'html' => $html));
        }
        $msg = isset($response['ret_msg']) ? $response['ret_msg'] : '系统抖动';
        return Response::json(array('status' => false, 'message' => $msg),400);

    }

    private function createMember($unionid='')
    {
        $memberService = new MemberService();
        $memberBindService = new MemberBindService();
        $dbRedis = Redis::connection('db');
        if($memberId = Session::get('mid')) {
            return $memberService->getByIncNum($memberId);
        }
        if($unionid) {
            if($memberId = $dbRedis->get('h5memberbind:'.$unionid)) {
                return $memberService->getByIncNum($memberId);
            }
            $memberBind = $memberBindService->getBindByUnionid($unionid);
            if($memberBind) {
                $dbRedis->set('h5memberbind:'.$unionid, $memberBind->member_id);
                Session::put('mid', $memberBind->member_id);
                return $memberService->getByIncNum($memberBind->member_id);
            }
        }

        $incNum = $dbRedis->incrBy(REDIS_MEMBER_INC,1);
        $data['mobile'] = '';
        $memberService->create($incNum, $data);
        if($unionid) {
            $dbRedis->set('h5memberbind:'.$unionid, $incNum);
        }
        Session::put('mid', $incNum);
        return $memberService->getByIncNum($incNum);
    }

    public function getOrder($shopId, $orderSn)
    {
        $orderService = new OrderService($shopId);
        $order = $orderService->getOrderBySn($orderSn);
        if($order) {
            $commissionService = new CommissionService();
            $commissionCredit = $commissionService->getExpectedCommissionCredit($order);
            $order['commission'] = $commissionCredit;
        }
        return Response::json($order);
    }
}
