<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommissionOrderDetail extends Model
{
    public $table = 'commission_order_detail';
    protected $guarded = ['id'];
    public $timestamps = false;
}
