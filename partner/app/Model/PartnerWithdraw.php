<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PartnerWithdraw extends Model
{
    public $table = 'partner_withdraw';
    protected $guarded = ['id'];
}