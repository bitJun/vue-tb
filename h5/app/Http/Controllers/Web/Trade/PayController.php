<?php

namespace App\Http\Controllers\Web\Trade;

use App\Http\Controllers\Controller;

class PayController extends Controller
{
    public function pay($token)
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return redirect('/wxpay/'.$token);
        } else {
            return redirect('/alipay/'.$token);
        }
    }
}
