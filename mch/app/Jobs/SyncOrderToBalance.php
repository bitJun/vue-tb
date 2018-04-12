<?php

namespace App\Jobs;

use App\Model\Order;
use App\Model\Shop;
use App\Model\ShopBalanceDetail;
use App\Model\ShopTradeSetting;
use App\Services\Order\OrderSettledService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SyncOrderToBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $servcie = new OrderSettledService($this->tableSuffix,$this->order_sn);
        $servcie->handle();
    }

}
