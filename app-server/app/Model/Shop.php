<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $connection = 'modian_mysql';
    public $table = 'shop';
    protected $guarded = ['id'];
}
