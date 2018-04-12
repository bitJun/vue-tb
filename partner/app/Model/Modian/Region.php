<?php

namespace App\Model\Modian;


use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $connection = 'modian_mysql';
    protected $table = 'region';
    protected $guarded = ['id'];
    public $timestamps = false;
}
