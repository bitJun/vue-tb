<?php

namespace App\Services\Member;

use App\Model\MemberLevel;
use App\Services\Logs\LogsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MemberLevelService
{
    protected $memberLevelModel = '';
    public function __construct(MemberLevel $memberLevel) {
        $this->memberLevelModel = $memberLevel;
    }

    public function getMemberLevel(){
        $shop_id = Auth::user()->shop_id;
        $data = $this->memberLevelModel->where('shop_id',$shop_id)->first();
        if(isset($data['keep_limit']) && $data['keep_limit']==0){
            $data['keep_limit'] = '';
        }
        return $data;
    }

    public function editMemberLevel($params){

        $result = $this->getMemberLevel();
        $data['name'] = (isset($params['name']) && $params['name']) ? $params['name'] : '合伙人';
        $data['credit_limit'] = isset($params['credit_limit']) ? $params['credit_limit'] : 0;
        $data['keep_limit'] = isset($params['keep_limit']) ? $params['keep_limit'] : 0;
        $data['enabled'] = isset($params['value']) && $params['value'] ? 1 : 0;

        $dbRedis = Redis::connection('db');
        if(isset($result['id']) && $result['id']){
            //修改
            $this->memberLevelModel->where('id',$result['id'])->update($data);
            $data['id'] = $result['id'];
            $data['shop_id'] = $result['shop_id'];
            $jsonData = json_encode($data);

            $dbRedis->set('setting:memberLevel_'.$result['shop_id'],$jsonData);

            //修改日志 TODO
            $logsService = new LogsService();
            $logsService->postLogs(['shop_id'=>$result['shop_id'],'source'=>'e_edit_level','content'=>$jsonData]);
            /*
            //重新计算合伙人
            if($result['keep_limit'] != $data['keep_limit'] && $data['enabled']==1) {

                $form_params = array('shop_id' => $result['shop_id']);
                $client = new Client(['base_uri' => getApiDomain(),'timeout' => 200,'verify' => false]);
                $client->post('api/reset_member_level.json',['form_params' => $form_params,'debug' => false])->getBody()->getContents();

            }*/

        }else{
            $shop_id = Auth::user()->shop_id;
            $data['shop_id'] = $shop_id;
            $result = $this->memberLevelModel->create($data);
            $data['id'] = $result->id;
            $dbRedis->set('setting:memberLevel_'.$shop_id,json_encode($data));
        }
        return ['status'=>true];
    }
}
