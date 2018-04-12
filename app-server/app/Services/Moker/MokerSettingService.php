<?php
namespace App\Services\Moker;

use App\Model\MokerSetting;

class MokerSettingService
{
    public function getSetting($mokerId)
    {
        $mokerSetting = MokerSetting::where('moker_id', $mokerId)->first();
        if($mokerSetting) {
            if(!isset($mokerSetting['msg_setting']) || !$mokerSetting['msg_setting']) {
                $mokerSetting['msg_setting'] = json_encode(['system'=>1, 'shop_comm'=>0, 'moker_comm'=>1, 'moker_join'=>0], JSON_NUMERIC_CHECK);
            }
        } else {
            $mokerSetting = new MokerSetting();
            $mokerSetting['msg_setting'] = json_encode(['system'=>1, 'shop_comm'=>0, 'moker_comm'=>1, 'moker_join'=>0], JSON_NUMERIC_CHECK);
        }
        return $mokerSetting;
    }

    public function setSetting($mokerId, $data)
    {
        $data['msg_setting'] = isset($data['msg_setting']) ? json_encode($data['msg_setting'], JSON_NUMERIC_CHECK) : '';
        $mokerSetting = MokerSetting::where('moker_id', $mokerId)->first();
        if($mokerSetting) {
            MokerSetting::where('moker_id', $mokerId)->update($data);
            $res =  MokerSetting::where('moker_id', $mokerId)->first();
        } else {
            $data['moker_id'] = $mokerId;
            $res = MokerSetting::create($data);
        }
        return $res;
    }
}