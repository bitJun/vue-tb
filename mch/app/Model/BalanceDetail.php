<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BalanceDetail extends Model
{
    protected $table = 'balance_detail';

    protected $guarded = ['id'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
