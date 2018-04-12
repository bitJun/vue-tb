<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberLevelInfo extends Model
{
    protected $table = 'member_level_info';

    protected $guarded = ['id'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
