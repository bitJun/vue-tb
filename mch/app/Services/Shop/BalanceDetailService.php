<?php

namespace App\Services\Shop;

use App\Model\ShopBalanceDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BalanceDetailService
{
    public function getBalanceDetails($params)
    {
        $shop_id = Auth::user()->shop_id;
        $query = ShopBalanceDetail::where('shop_id',$shop_id);
        if(isset($params['order_sn']) && $params['order_sn'])
        {
            $query->where('order_sn',$params['order_sn']);
        }
        if(isset($params['date_start']) && $params['date_start'])
        {
            $query->where('created_at','>',$params['date_start']);
        }
        if(isset($params['date_end']) && $params['date_end'])
        {
            $query->where('created_at','<',$params['date_end']);
        }
        if(isset($params['type']) && $params['type'] != 'all')
        {
            $query->where('type',$params['type']);
        }

        $count = $query->count();
        if($count)
        {
            $data = $query->skip($params['offset'])->take($params['limit'])->orderBy('id','desc')->get()->toArray();
        }else{
            $data = [];
        }
        if($data)
        {
            $config = config('status.shop_balance_detail_status');
            foreach ($data as &$v)
            {
                $v['type'] = $config[$v['type']];
            }
        }
        return [
            '_count' => $count,
            'data' => $data
        ];
    }

    public function postDetail($params){

        $data = [
            'shop_id' => Auth::user()->shop_id,
            'order_sn' => $params['order_sn'],
            'cash' => $params['cash'],
            'balance'=>isset($params['balance']) ? $params['balance'] : 0.00,
            'type' => $params['type'],
            'memo' => isset($params['memo']) ? $params['memo'] : '',
            'created_at'=>Carbon::now()
        ];

        ShopBalanceDetail::create($data);
    }
}
