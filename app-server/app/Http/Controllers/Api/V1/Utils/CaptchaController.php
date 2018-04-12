<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/20
 * Time: 下午7:45
 */

namespace App\Http\Controllers\Api\V1\Utils;

use Illuminate\Support\Facades\Session;
use Mews\Captcha\Facades\Captcha;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class CaptchaController extends Controller
{
    use Helpers;

    public function captcha()
    {
        $captcha =  Captcha::create('taotui');
        return $captcha;
    }
}