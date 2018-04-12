<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopApply extends Model
{
    protected $connection = 'modian_mysql';
    public $table = 'shop_apply';
    protected $guarded = ['id'];
}
