<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 15/8/28
 * Time: 下午4:41
 */
namespace App\Services\MobileSms;

use App\Jobs\SendMsg;
use Illuminate\Support\Facades\Redis;

class MobileSmsService
{
    public function sendVerifySms($mobile)
    {
        $code = $this->_createSmsStr();
        $params = "{\"code\":\"".$code."\"}";
        $tplId = env("SHORT_MESSAGE_TEMPLATE");

        Redis::set('h5captcha:'.$mobile,$code);
        Redis::expire('h5captcha:'.$mobile,120);
        $sendMsgJob = (new SendMsg($mobile, $tplId, $params));
        dispatch($sendMsgJob);
        return true;
    }

    private function _createSmsStr()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $str = str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
        return $str;
    }

    public function checkVerifyCode($verifyCode, $mobile)
    {
        $cacheVerifyCode = Redis::get('h5captcha:'.$mobile);
        if($cacheVerifyCode && $cacheVerifyCode == $verifyCode) {
            return true;
        }else{
            return false;
        }
    }
}