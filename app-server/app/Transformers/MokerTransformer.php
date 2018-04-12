<?php
namespace App\Transformers;
use App\Model\Moker;
use League\Fractal\TransformerAbstract;

class MokerTransformer extends TransformerAbstract {

    public function transform(Moker $moker) {
        return [
            'id' => $moker['id'],
            'name' => $moker['name'],
            'avatar' => $moker['avatar'] ? getImageUrl($moker['avatar']) : '',
            'mobile' => substr_replace($moker['mobile'], '****', 3, 4),
            'gender' => $moker['gender'],
            'birthday' => $moker['birthday'],
            'province_id' => $moker['province_id'],
            'city_id' => $moker['city_id'],
            'district_id' => $moker['district_id'],
        ];
    }
}