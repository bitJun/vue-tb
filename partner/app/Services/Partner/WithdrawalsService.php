<?php

namespace App\Services\Partner;

use App\Model\PartnerBank;
use App\Model\PartnerWithdraw;
use App\Model\Partner;
use App\Model\Modian\ShopBank;
use App\Services\Utils\RegionService;

class WithdrawalsService {

    public function postAccounts($data) {
        $cash = PartnerBank::where('bank_no', $data["bank_no"])->where("partner_id", "!=", $data["partner_id"])->first();
        $Accounts = PartnerBank::where('partner_id', $data["partner_id"])->first();
        if (empty($Accounts)) {
            $PartnerBank = PartnerBank::create($data);
        } else {
            $PartnerBank = PartnerBank::where('partner_id', $data["partner_id"])->update($data);
        }
        if ($PartnerBank) {
            return array("msg" => "操作成功", "status" => TRUE);
        }
    }

    public function postRecord($data) {
        $Partner = Partner::select("id", "balance")->where("id", $data["partner_id"])->first();
        if ($Partner["balance"] < $data["amount"]) {
            return false;
        }
        $PartnerWithdraw = PartnerWithdraw::create($data);
        $balance = $Partner["balance"] - $data["amount"];
        //修改余额
        Partner::where('id', $data["moker_id"])->update(['balance' => $balance]);
        if (!$PartnerWithdraw->id) {
            return false;
        } else {
            return true;
        }
    }

    public function getRecord($data) {
        $count = PartnerWithdraw::where('partner_id', $data["partner_id"])->count();
        $array = PartnerWithdraw::where('partner_id', $data["partner_id"])->skip($data["offset"])
                        ->limit($data["limit"])
                        ->orderBy('id', 'desc')->get();
        if (!$array) {
            $array = array();
        }
        $cash["total"] = $count;
        $cash["data"] = $array;
        return $cash;
    }

    public function getAccounts($data) {
        $Accounts = PartnerBank::where('partner_id', $data["partner_id"])->first();
        if (!$Accounts) {
            $Accounts = array();
        }
        $RegionService = new RegionService();
        $province = $RegionService->getRegion($Accounts["bank_province"]);
        $Accounts["province"] = $province["name"];
        $city = $RegionService->getRegion($Accounts["bank_city"]);
        $Accounts["city"] = $city["name"];
        $district = $RegionService->getRegion($Accounts["bank_district"]);
        $Accounts["district"] = $district["name"];
        return $Accounts;
    }

    public function putAccounts($id, $data) {
        if ($PartnerBank == 0) {
            return array("msg" => "修改失败！", "status" => FALSE);
        }
        return array("msg" => "修改成功！", "status" => true);
    }

    public function getBank() {
        $PartnerBank = ShopBank::get()->toArray();
        return $PartnerBank;
    }

}
