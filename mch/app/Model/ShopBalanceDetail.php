<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopBalanceDetail extends Model
{
    protected $table = 'shop_balance_detail';
    protected $guarded = ['id'];

    public $timestamps = false;
}
