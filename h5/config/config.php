<?php
//redis 自增 key
!defined('REDIS_MEMBER_INC') && define('REDIS_MEMBER_INC','increment:member_id');

!defined('SELF_PERCENT') && define('SELF_PERCENT',0);//买单消费送魔豆比例
!defined('PARENT_PERCENT') && define('PARENT_PERCENT',0);//上级送魔豆(邀请人)
!defined('PARTNER_PERCENT') && define('PARTNER_PERCENT',0);//合伙人送魔豆

!defined('ORDER_AUTO_CANCELED') && define('ORDER_AUTO_CANCELED',10);      // 已关闭
!defined('ORDER_TOPAY') && define('ORDER_TOPAY',20);         // 待付款
!defined('ORDER_PAY') && define('ORDER_PAY',40);           // 已付款
