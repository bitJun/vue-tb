<?php
namespace App\Services\Utils;

use Illuminate\Support\Facades\Config;
use TaoTui\Aliyun\Dm\SingleSendSmsRequest;
use TaoTui\Aliyun\Facades\Aliyun;

class SmsService
{
    public function sendSms($mobile, $tplId, $params, $signName='')
    {
        $request = new SingleSendSmsRequest();
        $signName = $signName ? $signName : Config::get('aliyun.sms_sign_name');
        $request->setSignName($signName);/*签名名称*/
        $request->setTemplateCode($tplId);/*模板code*/
        $request->setRecNum($mobile);/*目标手机号*/
        $request->setParamString($params);/*模板变量，数字一定要转换为字符串*/
        $response = Aliyun::getAcsResponse($request);
        return $response;
    }
}