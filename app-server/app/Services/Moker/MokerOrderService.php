<?php

namespace App\Services\Moker;

use App\Model\MokerOrder;

class MokerOrderService {

    public function create($data) {
        return MokerOrder::create($data);
    }

    public function updateById($id,$update){
        return MokerOrder::where('id',$id)->update($update);
    }

}