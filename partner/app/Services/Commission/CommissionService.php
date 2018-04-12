<?php

namespace App\Services\Commission;

use App\Model\PartnerComm;
use App\Services\Utils\RegionService;
use App\Model\Partner;
use App\Model\Moker\Moker;
use App\Model\Shop;
use App\Model\Moker\MokerInvitation;
use App\Model\Moker\MokerShop;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class CommissionService {

    public function getCommissionlist($data) {
        $query = PartnerComm::where("partner_id", $data["partner_id"]);
        if (!empty($data["order_sn"])) {
            $query->where("order_sn", 'like', '%' . $data['order_sn'] . '%');
        }

        if (isset($data["type"]) && $data["type"] != '') {
            $query->where("type", $data["type"]);
        }
        if (!empty($data["start"])) {
            $start = date("Y-m-d H:i:s", $data["start"] / 1000);
            $query->where("created_at", ">=", $start);
        }

        if (!empty($data["end"])) {
            $end = date("Y-m-d H:i:s", $data["end"] / 1000);
            $query->where("created_at", "<=", $end);
        }
        $count = $query->count();
        $list = $query->skip($data['offset'])->take($data['limit'])->get()->toArray();
        $Commission = array();
        if (!empty($list)) {
            foreach ($list as $ary) {
                $array = array();
                $array["date"] = $ary["created_at"];
                $array["type"] = $ary["type"] = 1 ? "邀请魔客佣金" : "交易佣金";
                $array["order_sn"] = $ary["order_sn"];
                $array["order_amount"] = $ary["order_amount"];
                $array["comm"] = $ary["comm"];
                $array["id"] = $ary["id"];
                $Commission[] = $array;
            }
        }

        $res["total"] = $count;
        $res["data"] = $Commission;
        return $res;
    }

    public function getCommission($data) {
        $query = PartnerComm::where("id", $data["id"])->first();
        return $query;
    }

    public function getProfit($data) {
        $query = PartnerComm::where("partner_id", $data["partner_id"])->sum("comm");
        //我的收益
        return $query;
    }

    public function homepage($data) {
        //我的收益
        $Profit = PartnerComm::where("partner_id", $data["partner_id"])->sum("comm");
        //余额
        $partner = Partner::where("id", $data["partner_id"])->first();
        $balance = $partner["balance"];

        //魔客
        //今日新增
        $today = date('Y-m-d', time()) . " 00:00:00";
        $query = Moker::where("province_id", $partner["province_id"])->where("city_id", $partner["city_id"])->where("district_id", $partner["district_id"])->where("level_id", ">", "1");
        $add_list = $query->where("created_at", ">=", $today)->get()->toArray();
        $add_moker = count($add_list);
        //累积邀请魔客
        $moker_list = $query->get()->toArray();
        $accumulate_moker = count($moker_list);
        //累积邀约商家
        $moker_id = array();
        foreach ($moker_list as $moker_ary) {
            $moker_id[] = $moker_ary["id"];
        }
        $moker_shop = MokerShop::whereIn("moker_id", $moker_id)->count();

        //商家
        //今日新增
        $shop = Shop::where("province", $partner["province_id"])->where("city", $partner["city_id"])->where("district", $partner["district_id"]);
        $add_shop = $shop->where("created_at", ">=", $today)->count();

        //已入住
        $accumulate_shop = $shop->count();

        $array = array();
        $array["Profit"] = $Profit; //我的收入
        $array["balance"] = $balance; //余额
        $array["add_moker"] = $add_moker; //新增魔客
        $array["accumulate_moker"] = $accumulate_moker; //累积魔客
        $array["moker_shop"] = $moker_shop; //累积邀约商家
        $array["add_shop"] = $add_shop; //商家今日新增
        $array["accumulate_shop"] = $accumulate_shop; //已入驻商家
        return $array;
    }

}
