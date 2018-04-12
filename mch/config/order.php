<?php
!defined('ORDER_AUTO_CANCELED') && define('ORDER_AUTO_CANCELED',10);      // 已关闭
!defined('ORDER_TOPAY') && define('ORDER_TOPAY',20);         // 待付款
!defined('ORDER_PAY') && define('ORDER_PAY',40);           // 已付款

return [
    'status' => [
        ORDER_AUTO_CANCELED => '已关闭',
        ORDER_TOPAY => '待付款',
        ORDER_PAY   => '已付款'
    ],
    'type'  => [
        0   => '买单',
        1   => '充值'
    ]
];