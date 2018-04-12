<?php

namespace App\Console\Commands;

use App\Model\ShopTradeSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use TaoTui\Cashier\Facades\Llpay;

class AutoWithdraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoWithdraw';

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
        $this->AutoWithdraw();
    }

    public function AutoWithdraw(){
        $shopSettingModel = new ShopTradeSetting();
        $settings = $shopSettingModel->where('shop_id',128)->where('status',4)->get();
        if(!$settings)
            exit;

        foreach ($settings as $key => $row){
            $data =array(
                'user_id' => $row['shop_id']
            );
            $result = Llpay::singleuserquery($data);
            if($result['status'] != 1)
                continue;

            $ll_data = $result['data'];
            if(!isset($ll_data['card_no']))
                continue;

            //生成唯一打款单号
            mt_srand((double) microtime() * 1000000);
            $dt = date('Ymd');
            $cashpay_sn = $dt . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);

            $newEncrypter = new \Illuminate\Encryption\Encrypter( md5('modian'), Config::get( 'app.cipher' ) );
            $decrypted = $newEncrypter->decrypt( $row['pwd_pay'] );

            if($ll_data['cashout_amt'] > 0){ //有可提现金额
                $data =array(
                    'user_id' => $row['shop_id'],
                    'mobile' => $row['mob_bind'],
                    'pwd_pay' => $decrypted,
                    'dt_order' => time(),
                    'no_order' => $cashpay_sn,
                    'money_order' => $ll_data['cashout_amt'],
                    'created_at' => time('YmdHis'),
                    'card_no' => $ll_data['card_no'],
                    'type_register' => $ll_data['type_register']
                );
                $response = Llpay::cashoutcombineapply($data);
            }
        }

    }

}
