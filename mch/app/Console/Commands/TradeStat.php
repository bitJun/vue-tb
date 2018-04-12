<?php

namespace App\Console\Commands;

use App\Model\Shop;
use App\Services\Order\OrderService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TradeStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TradeStat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日交易额统计';

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
        $shopModel = new Shop();
        $shops = $shopModel->select('id')->get();

        $appointedDay = -1;
        $date = date("Y-m-d",strtotime($appointedDay." day"));
        //echo $date."\r\n";
        foreach ($shops as $key => $row){
            $shop_id = $row['id'];
            $orderService = new OrderService($shop_id);
            $result = $orderService->yestodayIncome($shop_id,$appointedDay);
            $data['shop_id'] = $shop_id;
            $data['amount'] = $result['amount'];
            $data['count'] = $result['count'];
            $data['date'] = $date;
            $data['created_at'] = Carbon::now();
            $tradeStatModel = new \App\Model\TradeStat();
            if($result['amount'] > 0 && !$tradeStatModel->where(array('shop_id' => $shop_id, 'date' => $date))->first()){
                $tradeStatModel->create($data);
            }

        }


    }


}
