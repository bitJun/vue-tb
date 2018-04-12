<?php
namespace App\Http\Controllers\Web\Utils;

use App\Http\Controllers\Controller;
use Mews\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    public function captcha()
    {
        $captcha =  Captcha::create('modian');
        return $captcha;
    }
}