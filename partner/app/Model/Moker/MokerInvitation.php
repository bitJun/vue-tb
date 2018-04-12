<?php

namespace App\Model\Moker;

use Illuminate\Database\Eloquent\Model;

class mokerInvitation extends Model {

    protected $connection = 'moker_mysql';
    protected $table = 'moker_invitation';
    protected $guarded = ['id'];
    public $timestamps = false;

}
