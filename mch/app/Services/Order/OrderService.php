<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/8/21
 * Time: 下午8:27
 */

namespace App\Services\Order;


use App\Model\Order;
use Illuminate\Support\Facades\Config;

class OrderService
{
    protected $tableSuffix = 0;
    public function __construct($shop_id) {
        $this->tableSuffix = $shop_id ? ltrim(substr($shop_id,-2),0) : 0;
    }

    /**
     * @param $params
     * @return mixed
     * 订单列表
     */
    public function getOrders($params){
        $orderModel = new Order($this->tableSuffix);
        $query = $orderModel->select('id','shop_id','member_id','nickname','mobile','title','order_sn','amount','status','type','created_at')->where('shop_id', $params['shop_id']);
        if(isset($params['order_sn']) && $params['order_sn'])
        {
            $query->where('order_sn',$params['order_sn']);
        }
        if(isset($params['mobile']) && $params['mobile'])
        {
            $query->where('mobile',$params['mobile']);
        }
        if(isset($params['status']) && $params['status'])
        {
            $query->where('status',$params['status']);
        }
        if(isset($params['type']) && $params['type'])
        {
            $query->where('type',$params['type']);
        }
        $data['_count'] = $query->count();
        if($data['_count'])
        {
            $orders = $query->orderBy('id','DESC')->take($params['limit'])->skip($params['offset'])->get()->toArray();
            $configarr =  Config::get('order');
            foreach ($orders as $key => $value){
                $orders[$key]['status'] = $configarr['status'][$value['status']];
                $orders[$key]['type'] = $configarr['type'][$value['type']];
            }
            $data['data'] = $orders;
        }else{
            $data['data'] = [];
        }
        $data['dayIncome'] = $this->dayIncome($params['shop_id']);
        return $data;
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * 订单详情
     */
    public function getOrder($params){
        $orderModel = new Order($this->tableSuffix);
        $order = $orderModel->select('id','nickname','mobile','title','order_sn','amount','status','type','created_at')->where(array('id' => $params['order_id'], 'shop_id' => $params['shop_id']))->first();
        $configarr =  Config::get('order');
        $order['status'] = $configarr['status'][$order['status']];
        $order['type'] = $configarr['type'][$order['type']];
        return $order;
    }

    /**
     * 当天的收入
     */
    public function dayIncome($shop_id){
        $orderModel = new Order($this->tableSuffix);
        $day = date("Y-m-d",strtotime("-1 day")).' 23:59:59';
        return $orderModel->where('shop_id',$shop_id)->where('status','>',ORDER_TOPAY)->where('created_at','>',$day)->sum('amount');
    }

    public function yestodayIncome($shop_id,$appointedDay){
        $date_start = date("Y-m-d",strtotime(($appointedDay + -1)." day"));
        $date_end = date("Y-m-d",strtotime($appointedDay." day"));
        $date_start = $date_start.' 23:59:59';
        $date_end   = $date_end.' 23:59:59';
        $orderModel = new Order($this->tableSuffix);
        $query = $orderModel->where('shop_id',$shop_id)->where('status',ORDER_PAY)->where('created_at','>',$date_start)->where('created_at','<=',$date_end);
        $amount = $query->sum('amount');
        $count = $query->count();
        /*if($amount > 0){
            echo 'shop_id:'.$shop_id.'、status:'.ORDER_PAY.'、'.$date_start.'----'.$date_end."\r\n";
        }*/
        return ['amount' => $amount, 'count' => $count];
    }
}