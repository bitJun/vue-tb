<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MemberBind extends Model
{
    public $table = 'member_bind';
    protected $guarded = ['id'];
}
