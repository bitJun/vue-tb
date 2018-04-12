<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/12/6
 * Time: 下午1:45
 */

namespace App\Services\Payment;

use App\Model\Payment;

class PaymentService {

    public function getPayments() {
        return Payment::select('id', 'name', 'logo', 'code')->where('status', 1)->get();
    }

    public function getPaymentByCode($code) {
        return Payment::select('config')->where('code', $code)->first();
    }

}
