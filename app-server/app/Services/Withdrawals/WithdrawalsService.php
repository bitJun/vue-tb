<?php

namespace App\Services\Withdrawals;

use Illuminate\Support\Facades\Config;
use App\Model\MokerCashAccount;
use App\Model\MokerWithdraw;
use App\Model\Moker;
use TaoTui\Cashier\Facades\Cashier;
use App\Services\Payment\PaymentService;

class WithdrawalsService {

    public function postAccounts($data) {
        if (!$data["alipay_name"] || !$data["alipay_account"]) {
            return FALSE;
        }
        $cash = MokerCashAccount::where('alipay_account', $data["alipay_account"])->first();
        if ($cash) {
            return "帐号已经存在，不能重复！";
        }
        $MokerCashAccount = MokerCashAccount::create($data);
        if ($MokerCashAccount->id) {
            return true;
        }
    }

    public function postRecord($data) {
        $Moker = Moker::select("id", "balance")->where("id", $data["moker_id"])->first();
        if ($Moker["balance"] < $data["amount"]) {
            return false;
        }
        $MokerWithdraw = MokerWithdraw::create($data);
        $balance = $Moker["balance"] - $data["amount"];
        //修改余额
        Moker::where('id', $data["moker_id"])->update(['balance' => $balance]);
        if (!$MokerWithdraw->id) {
            return false;
        } else {
            return true;
        }
    }

    public function getRecord($data) {
        $count = MokerWithdraw::where('moker_id', $data["moker_id"])->count();
        $array = MokerWithdraw::where('moker_id', $data["moker_id"])->skip($data["offset"])
                        ->limit($data["limit"])
                        ->orderBy('id', 'desc')->get();
        if (!$array) {
            $array = array();
        } else {
            foreach ($array as $ary) {
                if ($ary->status == 1) {
                    $array->status = 3;
                } else if ($ary->status == 2) {
                    $array->status = 4;
                }
            }
        }
        $cash["total"] = $count;
        $cash["data"] = $array;
        return $cash;
    }

    public function getAccounts($data) {
        $Accounts = MokerCashAccount::where('moker_id', $data["moker_id"])->first();
        if (!$Accounts) {
            $Accounts = array();
        }
        return $Accounts;
    }

    public function putAccounts($id, $data) {
        if (!$data["alipay_name"] || !$data["alipay_account"]) {
            return FALSE;
        }
        $MokerCashAccount = MokerCashAccount::where('moker_id', $id)->update($data);
        if ($MokerCashAccount == 0) {
            return false;
        }
        return true;
    }

    public function putExamine($array) {
        $MokerWithdraw = MokerWithdraw::where('moker_id', $array["moker_id"])->where("id", $array["id"])->first();
        if (empty($MokerWithdraw)) {
            return FALSE;
        }
        if (MokerWithdraw::where('id', $array["id"])->update(['status' => $array["status"]])) {
            if ($array["status"] == 1) {
                $Accounts = MokerCashAccount::where('moker_id', $array["moker_id"])->first();
                if (!empty($Accounts)) {
                    $paymentService = new PaymentService();
                    $payment = $paymentService->getPaymentByCode('alipay');
                    $config = json_decode($payment['config'], true);
                    mt_srand((double) microtime() * 1000000);
                    $dt = date('Ymd');
                    $cashpay_sn = $dt . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
                    $data['payment_sn'] = $cashpay_sn;  //打款支付单号
                    $data['alipay_account'] = $Accounts["alipay_account"]; //提现转账的支付宝账号
                    $data['amount'] = $MokerWithdraw["amount"]; //提现打款金额
                    $data['show_name'] = '魔店';  //付款方姓名 显示在收款方的账单详情页。如果该字段不传，则默认显示付款方的支付宝认证姓名或单位名称。
                    $data['real_name'] = $Accounts["alipay_name"]; //收款方真实姓名, 提现客户支付宝账号的真实姓名,必填,支付宝会验证
                    $data['remark'] = '收益提现'; //备注
                    
                    $result = Cashier::transfer($data, $config);
                    if ($result["status"] == 1) {
                        MokerWithdraw::where('id', $array["id"])->update(['trade_sn' => $result["trade_sn"],'payment_sn'=>$cashpay_sn]);
                        return true;
                    } else {
                        $moker = Moker::where('id', $array["moker_id"])->first();
                        Moker::where('id', $array["moker_id"])->update(['balance' => $moker["balance"] + $MokerWithdraw["amount"]]);
                        return false;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
