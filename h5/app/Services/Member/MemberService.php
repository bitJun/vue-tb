<?php
namespace App\Services\Member;

use App\Model\Member;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MemberService
{
    public function create($incNum,$data)
    {
        $memberModel = new Member($incNum);
        $data['id'] = $incNum;
        $memberModel->fill($data);
        $member = $memberModel->save($data);
        return $member;
    }

    public function getByIncNum($incNum){
        $memberModel = new Member($incNum);
        $member = $memberModel->where('id',$incNum)->first();
        return $member;
    }

    public function putMember($incNum,$data){
        $memberModel = new Member($incNum);
        //如有修改手机号则更新redis
        if(isset($data['mobile']) && $data['mobile']){
            $dbRedis = Redis::connection('db');
            $dbRedis->set('member:'.$data['mobile'],$incNum);
        }
        return $memberModel->where('id',$incNum)->update($data);
    }

    public function getByMobile($mobile){
        $dbRedis = Redis::connection('db');
        $member_id = $dbRedis->get('member:'.$mobile);
        if($member_id) {
            $memberModel = new Member($member_id);
            return $memberModel->where('id',$member_id)->first();
        } else {
            return false;
        }
    }

    public function getCurrentMemberId()
    {
        $oauthUser = session('wechat.oauth_user');
        $unionid = '';
        if($oauthUser) {
            $org = $oauthUser->getOriginal();
            if($org && isset($org['unionid'])) {
                $unionid = $org['unionid'];
            }
        }
        if($memberId = Session::get('mid')) {
            return $memberId;
        } elseif($unionid) {
            $dbRedis = Redis::connection('db');
            if($memberId = $dbRedis->get('h5memberbind:'.$unionid)) {
                return $memberId;
            }
        }
        return 0;
    }
}