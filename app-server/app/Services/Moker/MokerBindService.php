<?php
namespace App\Services\Moker;

use App\Model\MokerBind;

class MokerBindService
{
    public function create($data)
    {
        if(isset($data['moker_id']) && $data['moker_id']) {
            $bindData['moker_id'] = $data['moker_id'];
            $bindData['type'] = isset($data['type']) && $data['type'] ? $data['type'] : 0;
            $bindData['openid'] = isset($data['openid']) && $data['openid'] ? $data['openid'] : '';
            $bindData['unionid'] = isset($data['unionid']) && $data['unionid'] ? $data['unionid'] : '';
            $bindData['access_token'] = isset($data['access_token']) && $data['access_token'] ? $data['access_token'] : '';
            $bindData['refresh_token'] = isset($data['refresh_token']) && $data['refresh_token'] ? $data['refresh_token'] : '';
            $bindData['user_info'] = isset($data['user_info']) && $data['user_info'] ? json_encode($data['user_info']) : '';
            $bindData['expires_at'] = isset($data['expires_in']) && $data['expires_in']  ? date('Y-m-d H:i:s',time()+$data['expires_in']- 120)  : '';
            $mokerBind = MokerBind::create($bindData);
            return $mokerBind;
        } else {
            return false;
        }
    }

    public function getBindByOpenid($openid)
    {
        return MokerBind::where('openid', $openid)->first();
    }

    public function getBind($mokerId)
    {
        return MokerBind::where('moker_id', $mokerId)->first();
    }
}