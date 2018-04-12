<?php
namespace App\Services\Member;

use App\Model\MemberBind;

class MemberBindService
{
    public function getBindByUnionid($unionid)
    {
        return MemberBind::where('unionid', $unionid)->first();
    }
}