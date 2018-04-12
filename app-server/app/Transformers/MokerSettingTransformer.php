<?php
namespace App\Transformers;
use App\Model\MokerSetting;
use League\Fractal\TransformerAbstract;

class MokerSettingTransformer extends TransformerAbstract {

    public function transform(MokerSetting $mokerSetting) {
        return [
            'msg_setting' => json_decode($mokerSetting['msg_setting'], true)
        ];
    }
}