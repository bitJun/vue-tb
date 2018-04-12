<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use TaoTui\Cashier\Facades\Llpay;

class AutoWithdraw implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data =array(
            'user_id' => $this->data['shop_id']
        );
        $result = Llpay::singleuserquery($data);
        if($result['status'] != 1)
            return false;

        $ll_data = $result['data'];
        //生成唯一打款单号
        mt_srand((double) microtime() * 1000000);
        $dt = date('Ymd');
        $cashpay_sn = $dt . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);

        $newEncrypter = new \Illuminate\Encryption\Encrypter( md5('modian'), Config::get( 'app.cipher' ) );
        $decrypted = $newEncrypter->decrypt( $this->data['pwd_pay'] );

        if($ll_data['cashout_amt'] > 0){ //有可提现金额
            $data =array(
                'user_id' => $this->data['shop_id'],
                'mobile' => $this->data['mob_bind'],
                'pwd_pay' => $decrypted,
                'dt_order' => time(),
                'no_order' => $cashpay_sn,
                'money_order' => $ll_data['cashout_amt'],
                'created_at' => time('YmdHis'),
                'card_no' => $ll_data['card_no'],
                'type_register' => $ll_data['type_register']
            );
            Llpay::cashoutcombineapply($data);
        }

    }
}
