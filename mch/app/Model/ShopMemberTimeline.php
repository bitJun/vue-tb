<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopMemberTimeline extends Model
{
    protected $table = 'shop_member_timeline';

    protected $guarded = ['id'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
