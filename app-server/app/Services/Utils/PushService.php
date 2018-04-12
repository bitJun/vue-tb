<?php
namespace App\Services\Utils;

use Illuminate\Support\Facades\Config;
use TaoTui\Aliyun\Facades\Aliyun;
use TaoTui\Aliyun\Push\PushRequest;

/**
 * Class PushService
 * @package App\Services\Utils
 * 推送服务
 */
class PushService
{
    public function push($params)
    {
        $target = isset($params['target']) ? $params['target'] : 'DEVICE';
        $targetValue = isset($params['targetValue']) ? $params['targetValue'] : '';
        $deviceType = isset($params['deviceType']) ? $params['deviceType'] : 'ALL';
        $pushType = isset($params['pushType']) ? $params['pushType'] : 'NOTICE';
        $title = isset($params['title']) ? $params['title'] : '';
        $body = isset($params['body']) ? $params['body'] : '';
        $iOSBadge = isset($params['iOSBadge']) ? $params['iOSBadge'] : '';
        $iOSSilentNotification = isset($params['iOSSilentNotification']) ? $params['iOSSilentNotification'] : 'false';
        $iOSMutableContent = isset($params['iOSMutableContent']) ? $params['iOSMutableContent'] : 'false';
        $iOSMusic = isset($params['iOSMusic']) ? $params['iOSMusic'] : 'default';
        $iOSApnsEnv = isset($params['iOSApnsEnv']) ? $params['iOSApnsEnv'] : 'PRODUCT';
        $iOSRemind = isset($params['iOSRemind']) ? $params['iOSRemind'] : 'false';
        $iOSRemindBody = isset($params['iOSRemindBody']) ? $params['iOSRemindBody'] : '';
        $iOSExtParameters = isset($params['iOSExtParameters']) ? $params['iOSExtParameters'] : '';
        $androidNotifyType = isset($params['androidNotifyType']) ? $params['androidNotifyType'] : 'BOTH';
        $androidNotificationBarType = isset($params['androidNotificationBarType']) ? $params['androidNotificationBarType'] : 0;
        $androidOpenType = isset($params['androidOpenType']) ? $params['androidOpenType'] : 'NONE';
        $androidOpenUrl = isset($params['androidOpenUrl']) ? $params['androidOpenUrl'] : '';
        $androidActivity = isset($params['androidActivity']) ? $params['androidActivity'] : '';
        $androidMusic = isset($params['androidMusic']) ? $params['androidMusic'] : 'default';
        $androidXiaoMiActivity = isset($params['androidXiaoMiActivity']) ? $params['androidXiaoMiActivity'] : '';
        $androidXiaoMiNotifyTitle = isset($params['androidXiaoMiNotifyTitle']) ? $params['androidXiaoMiNotifyTitle'] : '';
        $androidXiaoMiNotifyBody = isset($params['androidXiaoMiNotifyBody']) ? $params['androidXiaoMiNotifyBody'] : '';
        $androidExtParameters = isset($params['androidExtParameters']) ? $params['androidExtParameters'] : '';
        $pushTime = isset($params['pushTime']) ? $params['pushTime'] : '';
        $expireTime = isset($params['expireTime']) ? $params['expireTime'] : '';
        $storeOffline = isset($params['storeOffline']) ? $params['storeOffline'] : 'false';

        $request = new PushRequest();
        // 推送目标
        $request->setAppKey(Config::get('aliyun.push_app_key'));
        $request->setTarget($target); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
        $targetValue && $request->setTargetValue($targetValue); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
        $request->setDeviceType($deviceType); //设备类型 ANDROID iOS ALL.
        $request->setPushType($pushType); //消息类型 MESSAGE NOTICE
        $title && $request->setTitle($title); // 消息的标题
        $request->setBody($body); // 消息的内容
        // 推送配置: iOS
        $iOSBadge && $request->setiOSBadge($iOSBadge); // iOS应用图标右上角角标
        $request->setiOSSilentNotification($iOSSilentNotification);//是否开启静默通知
        $request->setiOSMutableContent($iOSMutableContent);//是否使能iOS通知扩展处理（iOS 10+）
        $request->setiOSMusic($iOSMusic); // iOS通知声音
        $request->setiOSApnsEnv($iOSApnsEnv);//iOS的通知是通过APNs中心来发送的，需要填写对应的环境信息。"DEV" : 表示开发环境 "PRODUCT" : 表示生产环境
        $request->setiOSRemind($iOSRemind); // 推送时设备不在线（既与移动推送的服务端的长连接通道不通），则这条推送会做为通知，通过苹果的APNs通道送达一次(发送通知时,Summary为通知的内容,Message不起作用)。注意：离线消息转通知仅适用于生产环境
        $iOSRemindBody && $request->setiOSRemindBody($iOSRemindBody);//iOS消息转通知时使用的iOS通知内容，仅当iOSApnsEnv=PRODUCT && iOSRemind为true时有效
        $iOSExtParameters && $request->setiOSExtParameters($iOSExtParameters); //自定义的kv结构,开发者扩展用 针对iOS设备
        // 推送配置: Android
        $request->setAndroidNotifyType($androidNotifyType);//通知的提醒方式 "VIBRATE" : 震动 "SOUND" : 声音 "BOTH" : 声音和震动 NONE : 静音
        $request->setAndroidNotificationBarType($androidNotificationBarType);//通知栏自定义样式0-100
        $request->setAndroidOpenType($androidOpenType);//点击通知后动作 "APPLICATION" : 打开应用 "ACTIVITY" : 打开AndroidActivity "URL" : 打开URL "NONE" : 无跳转
        $androidOpenUrl && $request->setAndroidOpenUrl($androidOpenUrl);//Android收到推送后打开对应的url,仅当AndroidOpenType="URL"有效
        $androidActivity && $request->setAndroidActivity($androidActivity);//设定通知打开的activity，仅当AndroidOpenType="Activity"有效
        $request->setAndroidMusic($androidMusic);//Android通知音乐
        $androidXiaoMiActivity && $request->setAndroidXiaoMiActivity($androidXiaoMiActivity);//设置该参数后启动小米托管弹窗功能, 此处指定通知点击后跳转的Activity（托管弹窗的前提条件：1. 集成小米辅助通道；2. StoreOffline参数设为true
        $androidXiaoMiNotifyTitle && $request->setAndroidXiaoMiNotifyTitle($androidXiaoMiNotifyTitle);
        $androidXiaoMiNotifyBody && $request->setAndroidXiaoMiNotifyBody($androidXiaoMiNotifyBody);
        $androidExtParameters && $request->setAndroidExtParameters($androidExtParameters); // 设定android类型设备通知的扩展属性
        // 推送控制
        //$pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+3 second'));//延迟3秒发送
        $pushTime && $request->setPushTime($pushTime);
        //$expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));//设置失效时间为1天
        $expireTime && $request->setExpireTime($expireTime);
        $request->setStoreOffline($storeOffline); // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
        $response = Aliyun::getAcsResponse($request);
        return $response;
    }
}