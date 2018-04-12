<?php
namespace App\Services\Credit;

use App\Services\Commission\CommissionService;
use App\Services\Member\MemberService;
use App\Services\Order\OrderService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class GiveCreditService
{

    public function giveCredit($params){

        $key='h5giveCredit:'.$params['order_id'];//令牌键值防止队列重复

        $result = false;
        if(Redis::incr($key) == 1){
            $result = Redis::expire($key,1);
        }

        if(!$result){
            return array('message'=>'重复提交','status'=>false);
        }
        //是否是老用户
        $memberService = new MemberService();
        $member = $memberService->getByMobile($params['mobile']);
        if(!$member){
            return array('message'=>'用户获取失败','status'=>false,'new_user'=>1);
        }

        //查订单计算魔豆数量
        $commissionService = new CommissionService();
        $detail = $commissionService->getCommissionCredit($params['shop_id'], $params['order_id'],$params['member_id']);

        $credit = $detail['commission_credit'];
        $response = ['credit'=>$credit,'member_id'=>$member['id']];

        $orderService = new OrderService($detail['shop_id']);
        $tmpOrder = $orderService->getOrder($params['order_id']);
        if($tmpOrder['mobile']){
            return array('message'=>'请勿重复领取','status'=>false);
        }

        if($credit){
            $form_params = array('shop_id' => $detail['shop_id'], 'order_id' => $params['order_id'],'member_id'=>$member['id'],'type'=>4,'credit'=>$credit);

            $client = new Client(['base_uri' => env('APP_API_URL'),'timeout' => 200,'verify' => false]);

            $response = $client->post('api/give_credit.json',['form_params' => $form_params,'debug' => false])->getBody()->getContents();

            //更新订单手机号
            $orderService->updateOrder(['mobile'=>$params['mobile'],'member_id'=>$member['id'],'nickname'=>$member['nickname']],$params['order_id']);
            Session::put('mid', $member['id']);
            //$response = json_decode($_response,true);
            //$response = $response['response'];
        }
        return $response;

    }
}