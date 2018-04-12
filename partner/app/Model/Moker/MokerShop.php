<?php

namespace App\Model\Moker;

use Illuminate\Database\Eloquent\Model;

class MokerShop extends Model {

    protected $connection = 'moker_mysql';
    protected $table = 'moker_shop';
    protected $guarded = ['id'];
    public $timestamps = false;

}
