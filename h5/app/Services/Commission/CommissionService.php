<?php
namespace App\Services\Commission;

use App\Model\CommissionOrderDetail;
use App\Model\CreditRule;
use App\Services\Order\OrderService;

class CommissionService
{
    public function getCommissionCredit($shopId, $orderId, $memberId)
    {
        $res = CommissionOrderDetail::where('order_id', $orderId)->where('member_id', $memberId)->first();
        if(!$res) {
            $orderService = new OrderService($shopId);
            $order = $orderService->getOrder($orderId);
            $res['commission_credit'] = $this->getExpectedCredit($order);
        }
        return $res;
    }

    public function getExpectedCommissionCredit($order)
    {
        $orderId = $order['id'];
        $memberId = $order['member_id'];
        $res = CommissionOrderDetail::where('order_id', $orderId)->where('member_id', $memberId)->first();
        if(!$res) {
            $res['commission_credit'] = $this->getExpectedCredit($order);
        }
        return $res;
    }

    private function getExpectedCredit($order)
    {
        $creditRule = CreditRule::where('shop_id', $order['shop_id'])->first();
        $commissionSetting = [
            'self_percent' => SELF_PERCENT,
            'parent_percent' => PARENT_PERCENT,
            'partner_percent' => PARTNER_PERCENT
        ];
        if($creditRule && $creditRule['rule']) {
            $commissionSetting = json_decode($creditRule['rule'], true);
        }
        $selfPercent = $commissionSetting['self_percent'];
        $amount = $order['amount'];

        $commissionCredit = floor($amount*$selfPercent/100);
        $commissionCredit = $commissionCredit > 0 ? $commissionCredit : 0;
        return $commissionCredit;
    }
}