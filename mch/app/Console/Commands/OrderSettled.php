<?php

namespace App\Console\Commands;

use App\Jobs\SyncOrderToBalance;
use App\Model\Order;
use Illuminate\Console\Command;

class OrderSettled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrderSettled{--tableSuffix=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单结算';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tableSuffix = $this->option('tableSuffix');
        $this->selectOrder($tableSuffix);
    }

    public function selectOrder($tableSuffix = 0){
            $orderModel = new Order($tableSuffix);
            $orders = $orderModel->select('id','shop_id','order_sn','amount')->where(array('status' => ORDER_PAY, 'is_settled' => 1))->get();
            if($orders){
                $orders = $orders->toArray();
                foreach ($orders as $key => $val){
                    if($val['amount'] > 0){
                        $job = (new SyncOrderToBalance($tableSuffix,$val['order_sn']))->onQueue('orderSettled');
                        dispatch($job);
                    }
                }
            }
    }

}
