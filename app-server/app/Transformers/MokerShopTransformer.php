<?php
namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class MokerShopTransformer extends TransformerAbstract {

    public function transform($mokerShop) {
        return [
            'id'    => $mokerShop['id'],
            'shop_apply_id'=>$mokerShop['shop_apply_id'],
            'shop_id'   => $mokerShop['shop_id'],
            'shop_name' => $mokerShop['shop_name'],
            'shop_logo' => getImageUrl($mokerShop['shop_logo']),
            'status'    => $mokerShop['status'],
            'today_comm' => $mokerShop['today_comm'],
            'total_comm' => $mokerShop['total_comm'],
            'created_at' => date('Y-m-d  H:i:s',strtotime($mokerShop['created_at']))
        ];
    }
}