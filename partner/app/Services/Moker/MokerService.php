<?php

namespace App\Services\Moker;

use App\Model\Moker\Moker;
use App\Model\Moker\MokerInvitation;
use App\Model\Moker\MokerShop;
use App\Model\Moker\MokerLevel;
use App\Services\Utils\RegionService;
use App\Model\Partner;

class MokerService {

    public function getMokerlist($data) {
        //查询登录服务商的地区信息
        $partner = Partner::where("id", $data["partner_id"])->first();
        //通过地区查找魔客基本信息
        $query = Moker::where("province_id", $partner["province_id"])->where("city_id", $partner["city_id"])->where("district_id", $partner["district_id"])->where("level_id", ">", "1");
        if (!empty($data['name'])) {
            $query->where("name", "like", '%' . $data['name'] . '%');
        }
        if (!empty($data['mobile'])) {
            $query->where("mobile", "like", '%' . $data['mobile'] . '%');
        }
        $count = $query->count();
        $moker = $query->orderBy('created_at', 'desc')->skip($data['offset'])->take($data['limit'])->get()->toArray();
        //查询魔客邀请人数
        $array = array();
        $moker_list = array();
        if (!empty($moker)) {
            foreach ($moker as $moker_id) {
                $invitation = MokerInvitation::where("moker_id", $moker_id["id"])->count();
                $array[$moker_id["id"]] = $invitation;
            }

            //魔客等级
            $MokerLevel = MokerLevel::get();
            $moker_level = array();
            foreach ($MokerLevel as $Level) {
                $moker_level[$Level["id"]] = $Level;
            }
            //查询魔客邀请商家
            $shop = array();
            foreach ($moker as $moker_id) {
                $shop_num = MokerShop::where("moker_id", $moker_id["id"])->where("status", "1")->count();
                $shop[$moker_id["id"]] = $shop_num;
            }
            foreach ($moker as $moker_ary) {
                $moker_array = [];
                $RegionService = new RegionService();
                $province = empty($moker_ary["province_id"]) ? "" : $RegionService->getRegion($moker_ary["province_id"]);
                $city = empty($moker_ary["city_id"]) ? "" : $RegionService->getRegion($moker_ary["city_id"]);
                $district = empty($moker_ary["district_id"]) ? "" : $RegionService->getRegion($moker_ary["district_id"]);
                $province_name = isset($province["name"]) ? $province["name"] : "暂无";
                $city_name = isset($city["name"]) ? $city["name"] : "暂无";
                $district_name = isset($district["name"]) ? $district["name"] : "暂无";
                $moker_array["addres"] = $province_name . $city_name . $district_name;
                $moker_array["id"] = $moker_ary["id"];
                $moker_array["level"] = $moker_level[$moker_ary["level_id"]]["name"];
                $moker_array["name"] = empty($moker_ary["name"]) ? "暂无" : $moker_ary["name"];
                $moker_array["mobile"] = $moker_ary["mobile"];
                $moker_array["shop"] = $shop[$moker_ary["id"]];
                $moker_array["invitation"] = $array[$moker_ary["id"]];
                $moker_list[] = $moker_array;
            }
        }
        $res["total"] = $count;
        $res["data"] = $moker_list;
        return $res;
    }

    public function getMoker($data) {
        $moker = Moker::where("id", $data["moker_id"])->first();
        if (empty($moker)) {
            return false;
        }
        if ($moker["gender"] == 1) {
            $moker["gender"] = "男";
        } else if ($moker["gender"] == 2) {
            $moker["gender"] = "女";
        } else {
            $moker["gender"] = "未知";
        }
        $RegionService = new RegionService();
        $province = $RegionService->getRegion($moker["province_id"]);
        $moker["province"] = $province["name"];
        $city = $RegionService->getRegion($moker["city_id"]);
        $moker["city"] = $city["name"];
        $district = $RegionService->getRegion($moker["district_id"]);
        $moker["district"] = $district["name"];
        $moker["avatar"] = getImageUrl($moker["avatar"]);

        //邀请人
        $invitation = MokerInvitation::where("moker_id", $moker["id"])->first();
        if (empty($invitation)) {
            $moker["invitation"] = "自己注册";
        } else {
            $invitation_list = Moker::where("id", $invitation["moker_id"])->first();
            $moker["invitation"] = $invitation_list["name"];
        }
        $moker["invitation"] = $moker["invitation"];

        //魔客等级
        $MokerLevel = MokerLevel::get();
        $moker_level = array();
        foreach ($MokerLevel as $Level) {
            $moker_level[$Level["id"]] = $Level;
        }
        $moker["level"] = $moker_level[$moker["level_id"]]["name"];
        //剩余入住商家数
        $limit = json_decode($moker_level[$moker["level_id"]]["priv"]);
        $shop_num = MokerShop::where("moker_id", $moker["id"])->where("status", "1")->count();
        $moker["shop_num"] = $limit->limit_shop - $shop_num;
        return $moker;
    }

}
