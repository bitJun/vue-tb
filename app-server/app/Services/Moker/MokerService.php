<?php

namespace App\Services\Moker;

use App\Model\Moker;
use App\Model\MokerInvitation;
use App\Model\MokerComm;
use App\Model\MokerShop;
use App\Model\MokerCashAccount;
use Illuminate\Support\Facades\Redis;
use App\Services\Message\MokerMessageService;
use Illuminate\Support\Facades\Auth;
use App\Services\Utils\RegionService;

class MokerService {

    public function getByMobile($mobile) {
        return Moker::where('mobile', $mobile)->first();
    }

    public function getById($id) {
        return Moker::where('id', $id)->first();
    }

    public function putMoker($id, $data) {
        return Moker::where('id', $id)->update($data);
    }

    public function bindDeviceId($id, $deviceId, $deviceType) {
        return Moker::where('id', $id)->update(array('device_id' => $deviceId, 'device_type' => $deviceType));
    }

    public function putPassword($mobile, $password) {
        $password = bcrypt($password);
        return Moker::where('mobile', $mobile)->update(['password' => $password]);
    }

    public function getCurrentMoker() {
        $currentMokerId = Auth::user()->id;
        $moker = Moker::where('id', $currentMokerId)->first();
        return $moker;
    }

    public function postMoker($params) {
        $data = [
            'mobile' => $params['mobile'],
            'password' => bcrypt($params['password']),
            'province_id' => $params['province_id'],
            'city_id' => $params['city_id'],
            'district_id' => $params['district_id']
        ];

        $moker = Moker::create($data);
        if ($moker && isset($params['invitee_moker_id']) && $params['invitee_moker_id']) {
            $mokerInviteeData = [
                'invitee_moker_id' => $moker['id'], //被邀请人
                'moker_id' => $params['invitee_moker_id'], //邀请人
            ];
            MokerInvitation::create($mokerInviteeData);
            //给上级发送模板消息
            $mokerMessageService = new MokerMessageService();
            $msgContent['title'] = '魔客加入';
            $msgContent['text'] = '手机号为' . $moker['mobile'] . '的用户成为您的下级，尚未成为魔客';
            $msgContent['name'] = $moker['mobile'];
            $messageData = [
                'moker_id' => $params['invitee_moker_id'],
                'type' => 3,
                'content' => json_encode($msgContent)
            ];
            //取邀请人设备id
            $invitee_device_id = Moker::where('id', $params['invitee_moker_id'])->value('device_id');
            $messagePushData = [
                'push_title' => $msgContent['title'],
                'push_body' => $msgContent['text'],
                'target_value' => $invitee_device_id,
                'moker_id' => $params['invitee_moker_id']
            ];
            $mokerMessageService->create($messageData, $messagePushData);
        }
        return $moker;
    }

    public function getInvitee($moker_id, $status, $offset, $limit) {
        $Invitee = array();
        $moker = MokerInvitation::where('moker_id', $moker_id)->get();
        if (empty($moker)) {
            return $Invitee;
        }
        $moker_comm = array();
        foreach ($moker as $moker_ary) {
            $Invitee_id[] = $moker_ary["invitee_moker_id"];
            $MokerComm_list = MokerComm::select("comm")->where('invitee_moker_id', $moker_ary["invitee_moker_id"])->first();
            $comm = 0;
            if (!empty($MokerComm_list)) {
                $comm = $MokerComm_list["comm"];
            }
            $moker_comm[$moker_ary["invitee_moker_id"]] = $comm;
        }
        $data = array();
        $number = 0;
        if ($status == 1) {
            $fuhao = ">";
        } else {
            $fuhao = "=";
        }
        if (!empty($Invitee_id)) {
            $query = Moker::whereIn("id", $Invitee_id)->where("level_id", $fuhao, 1);
            $number = $query->count();
            $moker_list = Moker::select("id", "created_at", "avatar", "name", "mobile")->whereIn("id", $Invitee_id)->where("level_id", $fuhao, 1)->skip($offset)->limit($limit)->orderBy('moker.id', 'desc')->get()->toArray();
            foreach ($moker_list as $moker_array) {
                $ary = array();
                $ary["comm"] = $moker_comm[$moker_array["id"]];
                $ary["created_at"] = $moker_array["created_at"];
                $ary["avatar"] = getImageUrl($moker_array["avatar"]);
                $ary["status"] = $status;
                $ary["name"] = $moker_array["name"] == "" ? substr($moker_array["mobile"], 0, 3) . "****" . substr($moker_array["mobile"], 7, 4) : $moker_array["name"];
                $ary["mobile"]=substr($moker_array["mobile"], 0, 3) . "****" . substr($moker_array["mobile"], 7, 4);
                $data[] = $ary;
            }
        }
        //累积收益
        $total = MokerComm::where('moker_id', $moker_id)->where("type", 1)->sum("comm");
        $array["total"] = isset($total) ? $total : 0;
        $array["number"] = $number;
        $array["data"] = $data;

        return $array;
    }

    public function getStatistics($data) {
        $res = array();
        //今日收益
        $today = date("Y-m-d", time());
        $profit = MokerComm::where('moker_id', $data["moker_id"])->where("created_at", ">=", $today)->sum("comm");
        $res["today_profit"] = $profit;

        //累积收益
        $profit_list = MokerComm::where('moker_id', $data["moker_id"])->sum("comm");
        $res["all_profit"] = $profit_list;

        //可提金额
        $Moker = Moker::where("id", $data["moker_id"])->first();
        $res["balance"] = empty($Moker) ? 0 : $Moker["balance"];

        //我的团队
        $MokerComm = MokerInvitation::where('moker_id', $data["moker_id"])->get();
        $invitee_moker_id = array();
        foreach ($MokerComm as $MokerComm_ary) {
            $invitee_moker = Moker::where('id', $MokerComm_ary["invitee_moker_id"])->first();
            if ($invitee_moker["level_id"] > 1) {
                $invitee_moker_id[] = $MokerComm_ary["invitee_moker_id"];
            }
        }
        $res["my_team"] = count($invitee_moker_id);

        //团队累积收益
        if (empty($invitee_moker_id)) {
            $invitee_list = 0;
        } else {
            $invitee_list = MokerComm::whereIn('moker_id', $invitee_moker_id)->sum("comm");
        }
        $res["team_profit"] = $invitee_list + $res["all_profit"];

        //我的店铺
        $moker_shop = MokerShop::where('moker_id', $data["moker_id"])->where("status", 1)->where("enabled", 1)->get();
        $shop_id = array();
        foreach ($moker_shop as $moker_shop_ary) {
            $shop_id[] = $moker_shop_ary["shop_id"];
        }
        $res["my_shop"] = count($shop_id);

        //店铺累积收益
        if (!empty($shop_id)) {
            $ShopComm = MokerComm::where('moker_id', $data["moker_id"])->whereIn("shop_id", $shop_id)->sum("comm");
        } else {
            $ShopComm = 0;
        }
        $res["shop_comm"] = $ShopComm;

        //是否支付魔客费用
        $res["is_payment"] = 0;
        if ($Moker["level_id"] > 1) {
            $res["is_payment"] = 1;
        }
        //是否添加店铺
        $MokerShop = MokerShop::where("moker_id", $data["moker_id"])->where("status", 1)->where("enabled", 1)->count();
        $res["is_addshop"] = 0;
        if ($MokerShop > 0) {
            $res["is_addshop"] = 1;
        }
        //是否添加提现账户
        $MokerCashAccount = MokerCashAccount::where("moker_id", $data["moker_id"])->count();
        $res["is_addaccount"] = 0;
        if ($MokerCashAccount > 0) {
            $res["is_addaccount"] = 1;
        }

        //未读消息数
        $key = "moker_message:unread_" . $data['moker_id'];
        $res['iOSBadge'] = 0;
        if ($badge = Redis::connection('db')->get($key)) {
            $res['iOSBadge'] = $badge;
        }
        $array["data"] = $res;
        return $array;
    }

    public function getmoker($id) {
        $moker = Moker::where('id', $id)->first();
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
        return $moker;
    }

}
