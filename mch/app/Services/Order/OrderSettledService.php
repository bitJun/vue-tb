<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/11/17
 * Time: 下午5:03
 */

namespace App\Services\Order;

use App\Model\Order;
use App\Model\Shop;
use App\Model\ShopBalanceDetail;
use App\Model\ShopTradeSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrderSettledService
{

    protected $tableSuffix;
    protected $order_sn;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tableSuffix,$order_sn)
    {
        $this->tableSuffix = $tableSuffix;
        $this->order_sn = $order_sn;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orderModel = new Order($this->tableSuffix);
        $order = $orderModel->where(array('order_sn' => $this->order_sn,'status' => ORDER_PAY, 'is_settled' => 1))->first();
        if(!$order)
            return;

        $shop = Shop::where('id',$order['shop_id'])->first();
        if(!$shop)
            return;

        $test['order']['amount'] = $order['amount'];
        $test['before']['expect'] = $shop['expect'];
        $test['before']['balance'] = $shop['balance'];

        $setting = $this->getTradeSetting($order['shop_id']);
        if(!$setting)  //没有配置审核通过支付,订单不结算
            return;

        if($shop['expect'] < $order['amount'])
            return;

        $poundage = 0; //手续费
        $settled = $order['amount']; //应结算至余额
        if($order['amount'] > 1.70){  //至少1.70元才能算出1分手续费 ,1.7以下不计算手续费
            $poundage = $order['amount'] * 0.006; //手续费
            $poundage = number_format($poundage,2,'.','');
            $settled = $order['amount'] - $poundage; //应结算至余额
        }

        $test['calculate']['poundage'] = $poundage;
        $test['calculate']['settled'] = $settled;

        //商户待结算
        $nosettle = $shop['expect'] - $order['amount'];
        //商户余额
        $balance = $shop['balance'] + $settled;
        $test['update']['expect'] = $nosettle;
        $test['update']['balance'] = $balance;
        DB::beginTransaction();
        try{
            $restShop = Shop::where('id',$shop['id'])->update(array('expect' => $nosettle, 'balance' => $balance));
            $resetOrder = $orderModel->where('id',$order['id'])->update(array('is_settled' => 2));
            $detail['shop_id'] = $shop['id'];
            $detail['order_sn'] = $order['order_sn'];
            $detail['cash'] = $settled;
            $detail['balance'] = $balance;
            $detail['type'] = $order['type'];
            if($order['type'] == 0){
                $memo = '买单';
            }else{
                $memo = '充值';
            }
            $detail['memo'] = $memo.'结算';
            $detail['created_at'] = Carbon::now();
            $newDetail = ShopBalanceDetail::create($detail);
            if ($restShop && $resetOrder && $newDetail)
            {
                DB::commit();
            }

        }catch (\Exception $e) {
            DB::rollBack();
        }

    }

    public function getTradeSetting($shop_id)
    {
        $key = 'setting:shop_trade_'.$shop_id;
        if(Redis::connection('db')->get($key))
        {
            return true;
        }else{
            $setting = ShopTradeSetting::select('id','shop_id','status')->where(array('shop_id' => $shop_id, 'status' => 4))->first();
            if(!$setting)
                return false;

            Redis::set($key,$setting);
            return true;
        }
    }
}