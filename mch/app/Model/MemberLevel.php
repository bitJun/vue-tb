<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberLevel extends Model
{
    protected $table = 'member_level';

    protected $guarded = ['id'];

    protected $dateFormat = 'Y-m-d H:i:s';
}
