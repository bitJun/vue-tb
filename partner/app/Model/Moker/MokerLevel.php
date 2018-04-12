<?php

namespace App\Model\Moker;

use Illuminate\Database\Eloquent\Model;

class MokerLevel extends Model {

    protected $connection = 'moker_mysql';
    protected $table = 'moker_level';
    protected $guarded = ['id'];
    public $timestamps = false;

}
