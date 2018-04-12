<?php
namespace App\Services\Message;

use App\Jobs\PushMsg;
use App\Model\MokerMessage;
use App\Services\Moker\MokerSettingService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class MokerMessageService
{
    public function getMokerMessages($mokerId, $offset=0, $limit=5, $msgs, $tableSuffix='', $nextOffset=0, $nextLimit=0)
    {
        $nextOffset = $nextOffset ? $nextOffset : $offset+$limit;
        $nextLimit = $nextLimit ? $nextLimit : $limit;
        $tableSuffix = $tableSuffix ? $tableSuffix : date('Ym', time());
        $currentTime = $tableSuffix . '01';
        $table = 'moker_message_' . $tableSuffix;
        if (Schema::hasTable($table)) {
            $msgModel = new MokerMessage();
            $messages = $msgModel->setTable($table)->where('moker_id', $mokerId)->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();
            if($messages) {
                $msgs = $msgs->concat($messages);
            }
            $c = count($messages);
            if ($c < $limit) {
                $tableSuffix = date('Ym', strtotime('-1 month', strtotime($currentTime)));
                $nlimit = $limit - $c;
                return $this->getMokerMessages($mokerId, 0, $nlimit, $msgs, $tableSuffix, $nlimit, $nextLimit);
            }
        } else {
            return ['data'=>$msgs, 'meta'=>['has_next'=>false]];
        }

        $next_table_suffix = $tableSuffix;
        return ['data' => $msgs, 'meta' => ['next_offset' => $nextOffset, 'next_limit' => $nextLimit, 'next_table_suffix' => $next_table_suffix, 'has_next' => true]];
    }

    public function create($data, $pushData)
    {
        $mokerSettingService = new MokerSettingService();
        $rules = [
            'moker_id' => 'required|integer|min:0',
            'type' => 'required|integer|min:0',
            'content' => 'required',
        ];
        $messages = [
            'moker_id.required'=>'魔客ID是必须的',
            'moker_id.integer'=>'非法的魔客ID',
            'moker_id.min'=>'非法的魔客ID',
            'type.required'=>'消息类型是必须的',
            'type.integer'=>'非法的消息类型',
            'type.min'=>'非法的消息类型',
            'content.required' => '消息内容是必须的'
        ];

        $validator = Validator::make($data, $rules, $messages);
        if($validator->fails()){
            $error = $validator->errors()->first();
            return $error;
        }
        $setting  = $mokerSettingService->getSetting($data['moker_id']);
        $msgSetting = json_decode($setting['msg_setting'],true);
        $on = true;
        //系统通知
        if($data['type'] == 0) {
            $on = isset($msgSetting['system']) && $msgSetting['system'] ? true : false;
        }
        //店铺收益
        if($data['type'] == 1) {
            $on = isset($msgSetting['shop_comm']) && $msgSetting['shop_comm'] ? true : false;
        }
        //魔客收益
        if($data['type'] == 2) {
            $on = isset($msgSetting['moker_comm']) && $msgSetting['moker_comm'] ? true : false;
        }
        //魔客加入
        if($data['type'] == 3) {
            $on = isset($msgSetting['moker_join']) && $msgSetting['moker_join'] ? true : false;
        }
        $table = 'moker_message_'.date('Ym', time());
        if (!Schema::hasTable($table)) {
            $this->createTable($table);
        }
        $msgModel = new MokerMessage();
        $msgModel->setTable($table);
        $msgModel->fill($data);
        $message = $msgModel->save($data);

        $key = "moker_message:unread_".$data['moker_id'];
        Redis::connection('db')->incr($key);
        if($on) {
            if($pushData && isset($pushData['target_value']) && $pushData['target_value']) {
                $this->pushMsg($pushData);
            }
        }
        return $message;
    }

    private function createTable($table)
    {
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('moker_id')->unsigned();
            $table->tinyInteger('type')->comment('消息类型  0:系统消息 1:店铺收益 2:魔客收益 3:魔客加入');
            $table->text('content');
            $table->timestamps();
            $table->index('moker_id', 'moker_idx');
        });
    }

    public function pushMsg($data)
    {
        $params['title'] = $data['push_title'];
        $params['body'] = $data['push_body'];
        $params['targetValue'] = $data['target_value'];
        $key = "moker_message:unread_".$data['moker_id'];
        if($badge = Redis::connection('db')->get($key)) {
            $params['iOSBadge'] = $badge;
        }//$params['iOSApnsEnv'] = 'DEV';
        $params['androidOpenType'] = 'ACTIVITY';
        $params['androidActivity'] = 'com.taotui8.moker.me.view.MyMessageActivity';
        $params['storeOffline'] = 'true';
        $job = new PushMsg($params);
        dispatch($job);
    }
}