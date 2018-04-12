<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 15/8/28
 * Time: 下午4:41
 */
namespace App\Services\MobileSms;

use App\Jobs\SendMsg;
use App\Services\Utils\SmsService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class MobileSmsService
{
    use DispatchesJobs;
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendMessage($data)
    {
        $mobile = $data['mobile'];//13968154713';
        $device_id = isset($data['device_id']) ? $data['device_id'] : 'nodeviceid';
        $captcha = isset($data['captcha']) ? $data['captcha'] : '';

        //判断要不要显示图片验证码，防刷
        $cacheKey = 'moker_app_sms_count'.$device_id.$mobile;
        if(!$captcha && Cache::get($cacheKey))
        {
            Cache::increment($cacheKey);
            return ['status'=>false,'url'=>url('/api/sms_captcha'),'code'=>30000];
        }else{
            Cache::put($cacheKey, 1, Carbon::now()->addMinutes(1));
        }

        /*Redis::set('captch'.$mobile,123456);
        Redis::expire('captch'.$mobile,120);
        return true;*/
        $this->doSend($mobile);

        //dd($resp);
        return ['status'=>true];
    }

    public function doSend($mobile)
    {
        $code = $this->_createSmsStr();
        $params = "{\"code\":\"".$code."\"}";
        $tplId = env("SHORT_MESSAGE_TEMPLATE");

        Redis::set('moker_app_code:'.$mobile,$code);
        Redis::expire('moker_app_code:'.$mobile,120);
        //$resp = $this->smsService->sendSms($mobile, $tplId, $params);
        //走队列发送短信
        $sendMsgJob = (new SendMsg($mobile, $tplId, $params));
        $this->dispatch($sendMsgJob);
        return true;
    }

    private function _createSmsStr()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $str = str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
        return $str;
    }
}