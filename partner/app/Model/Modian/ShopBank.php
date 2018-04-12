<?php

namespace App\Model\Modian;


use Illuminate\Database\Eloquent\Model;

class ShopBank extends Model
{
    protected $connection = 'modian_mysql';
    protected $table = 'shop_bank';
    protected $guarded = ['id'];
    public $timestamps = false;
}
