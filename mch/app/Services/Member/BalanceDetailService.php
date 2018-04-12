<?php

namespace App\Services\Member;

use App\Model\BalanceDetail;
use App\Model\Member;
use App\Model\MemberLevelInfo;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class BalanceDetailService
{

    public function getDetails($params)
    {
        $shop_id = isset($params['shop_id']) ? $params['shop_id'] : Auth::user()->shop_id;
        $member_id = isset($params['member_id']) ? $params['member_id'] : '';

        $where = ['shop_id'=>$shop_id];
        if($member_id){
            $where['member_id'] = $member_id;
        }
        $data['data'] = [];

        //手机号查询
        if(isset($params['mobile']) && $params['mobile'])
        {
            $dbRedis = Redis::connection('db');
            $member_id = $dbRedis->get('member:'.$params['mobile']);
            if($member_id){
                $memberModel = new Member($member_id);
                $member = $memberModel->where('id',$member_id)->first();
                $where['member_id'] = $member['id'];
            }else{
                $data['_count'] = 0;
                return $data;
            }
        }

        $memLevelInfoModel = new MemberLevelInfo();
        /*


        //根据等级查询
        if(isset($params['level']) && $params['level'] > -1){
            $operator = $params['level']==1 ? '>' : '=';
            $data['_count'] = $memLevelInfoModel->where($where)->where('level_id',$operator,0)->count();
            if($data['_count']){
                $memberList = $memLevelInfoModel->where($where)->where('level_id',$operator,0)->take($params['limit'])->skip($params['offset'])->get();
            }else{
                return $data;
            }

        }

        */

        $balanceDetailModel = new BalanceDetail();
        $query = $balanceDetailModel->where($where);

        if(isset($memberList) && $memberList){
            $memberIds = [];
            foreach($memberList as $k => $list){
                $memberIds[] = $list['member_id'];
            }
            $result = $query->whereIn('member_id',$memberIds)->get()->toArray();
        }else{
            $data['_count'] = $query->count();
            $result = $query->orderBy('id','DESC')->take($params['limit'])->skip($params['offset'])->get()->toArray();
        }


        foreach($result as $k=> &$_result){
            $_result['cash'] = $_result['cash'] >= 0 ? '+'.$_result['cash'] : '-'.$_result['cash'];
            $memberModel = new Member($_result['member_id']);
            $member = $memberModel->where('id',$_result['member_id'])->first();

            $_result['nickname'] = $member['nickname'];
            $_result['mobile'] = $member['mobile'];
            $_result['avatar'] = $member['avatar'] ? getImageUrl($member['avatar']):'/image/default_head_img.jpg';
            $_result['gender'] = $member['gender'];
            $_result['birthday'] = $member['birthday'];
            $_result['device_id'] = $member['device_id'];

            //等级
            $levelInfo = $memLevelInfoModel->where(array('shop_id'=>$shop_id,'member_id'=>$_result['member_id']))->first();
            $_result['level_id'] = $levelInfo['level_id'];
            $_result['level_name'] = $levelInfo['level_id'] ? '股东合伙人' : '高级会员';

            //累计积分
            $_result['total_credit'] = $levelInfo['total_credit'];
            //累计消费
            $_result['total_consume'] = $levelInfo['total_consume'];
            //等级积分
            $_result['level_credit'] = $levelInfo['level_credit'];
        }

        $data['data'] = $result;

        return $data;
    }
}
