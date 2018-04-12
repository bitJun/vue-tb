<?php
namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class MokerCommTransformer extends TransformerAbstract {

    public function transform($mokerComm) {
        return [
            'comm' => $mokerComm['comm'],
            'invitee_moker_id' => $mokerComm['invitee_moker_id'],
            'title' => $mokerComm['title'],
            'logo' => getImageUrl($mokerComm['logo']),
            'shop_id' => $mokerComm['shop_id'],
            'created_at' => date('Y-m-d  H:i:s',strtotime($mokerComm['created_at']))
        ];
    }
}