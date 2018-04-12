<?php
!defined('ORDER_AUTO_CANCELED') && define('ORDER_AUTO_CANCELED',10);      // 已关闭
!defined('ORDER_TOPAY') && define('ORDER_TOPAY',20);         // 待付款
!defined('ORDER_PAY') && define('ORDER_PAY',40);           // 已付款

//order表 type 类型
!defined('ORDER_TYPE_CASHIER') && define('ORDER_TYPE_CASHIER',0);//收银、买单
!defined('ORDER_TYPE_RECHARGE') && define('ORDER_TYPE_RECHARGE',1);//充值
!defined('ORDER_TYPE_INVITE_MOKER') && define('ORDER_TYPE_INVITE_MOKER',2); //邀请魔客

!defined('MODIAN') && define('MODIAN',8);
