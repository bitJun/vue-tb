<?php

namespace App\Services\Moker;


use App\Model\MokerLevel;

class MokerLevelService {

    public function getMokerLevel() {
        $level = MokerLevel::where('id',2)->first();
        $level['priv'] = json_decode($level['priv'],true);
        return $level;
    }

}
