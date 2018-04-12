<?php
namespace App\Services\Moker;

use App\Model\Moker;
use App\Model\MokerComm;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MokerCommService
{

    public function getCommission($params){

        $query = MokerComm::where('moker_id',$params['moker_id'])->select('shop_id','comm','type','invitee_moker_id','created_at');

        if(isset($params['type'])){
            $query->where('type',$params['type']);
        }
        if($params['source'] == 'today') {
            $today = Carbon::today();
            $query->where('created_at','>=',$today);
        }

        $result = $query->orderBy('created_at', 'desc')->paginate($params['limit']);

        return $result;
    }

    public function getCommSum($params){
        $query = MokerComm::where('moker_id',$params['moker_id']);

        if(isset($params['shop_id'])){
            $query->where('shop_id',$params['shop_id']);
        }

        if(isset($params['type'])){
            $query->where('type',$params['type']);
        }
        if(isset($params['source']) && $params['source'] == 'today') {
            $today = Carbon::today();
            $query->where('created_at','>=',$today);
        }
        $comm = $query->sum('comm');
        return $comm ? $comm : '0.00';
    }

    /**
     * 富豪榜
     * $type all所有、month月排行、weed周排行
     */
    public function ranking_list($type = 'all',$moker_id = 0,$select = 0){
        $date = '';
        $key = 'ranking_list';
        $result = ['amount' => 0, 'in' => 0, 'data' => []];
        if($type == 'month'){
            $date = date("Y-m-d",strtotime("-30 day")).' 00:00:00';
            $key = 'month_'.$key;
        }elseif($type == 'week'){
            $date = date("Y-m-d",strtotime("-7 day")).' 00:00:00';
            $key = 'week_'.$key;
        }

        $data = Redis::get($key);
        if(!$data || $select == 1){
            $query = MokerComm::select('moker_id',DB::raw("sum(`comm`) as amount"));
            if($date){
                $query->where('created_at','>',$date);
            }
            $data = $query->groupBy('moker_id')->orderBy('amount','DESC')->limit(10)->get()->toArray();
            Redis::set($key,json_encode($data));
        }else{
            $data = json_decode($data,true);
        }

        if($moker_id){
            $mokerQuery = MokerComm::where('moker_id',$moker_id);
            if($date){
                $mokerQuery->where('created_at','>',$date);
            }
            $mokerQuery->where('created_at','<',date("Y-m-d").' 00:00:00');
            $result['amount'] = $mokerQuery->sum('comm');

            foreach ($data as $key => $val){
                if($moker_id == $val['moker_id']){
                    $result['in'] = 1;
                }
                $moker = Moker::where('id',$val['moker_id'])->first();
                $data[$key]['name'] = $moker['name'];
                $data[$key]['avatar'] = $moker['avatar'];
            }
            $result['data'] = $data;
        }
        return $result;
    }
}