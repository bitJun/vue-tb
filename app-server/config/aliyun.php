<?php
return [
    'access_key'       => env('ALIYUN_ACCESS_KEY', 'ACCESS KEY'),
    'access_secret'    => env('ALIYUN_ACCESS_SECRET', 'ACCESS SECRET'),
    'region_id'        => env('ALIYUN_REGION_ID', 'cn-hangzhou'),
    'push_app_key'     => env('ALIYUN_PUSH_APP_KEY', ''),
    'sms_sign_name'        => env('ALIYUN_SMS_SIGNNAME', '淘推管家')
];