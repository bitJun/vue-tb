<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'modian_mysql';
    public $table = 'payment';
    protected $guarded = ['id'];
}
