<?php
namespace App\Services\Order;


use App\Model\Order;

class OrderService
{
    protected $tableSuffix = 0;
    public function __construct($shop_id) {
        $this->tableSuffix = $shop_id ? ltrim(substr($shop_id,-2),0) : 0;
    }

    public function create($data)
    {
        $orderModel = new Order($this->tableSuffix);
        $orderModel->fill($data);
        $order = $orderModel->save($data);
        if($order){
            return $data;
        }
        else{
            return false;
        }
    }

    public function getOrderBySn($orderSn) {
        $orderModel = new Order($this->tableSuffix);
        $order = $orderModel->where('order_sn', $orderSn)->first();
        return $order;
    }

    public function getOrder($order_id){
        $orderModel = new Order($this->tableSuffix);
        $order = $orderModel->where('id', $order_id)->first();
        return $order;
    }

    public function updateOrder($data,$order_id){
        $orderModel = new Order($this->tableSuffix);
        return $orderModel->where(array('id' => $order_id))->update($data);
    }

    public function get_order_sn($letter = 'E'){
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 10000000);
        $dt = date('Ymdhis');
        $order_sn = $letter . $dt . str_pad(mt_rand(1, 999999), 5, '0', STR_PAD_LEFT). $this->tableSuffix;
        $order_model = new Order($this->tableSuffix);
        $order = $order_model->where(array('order_sn' => $order_sn))->first();
        if (!$order) {
            return $order_sn;
        }

        /* 如果有重复的，则重新生成 */
        return $this->get_order_sn($letter);
    }


}