<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/9/20
 * Time: 下午3:21
 */

!defined('SHOP_BALANCE_DETAIL_PAY') && define('SHOP_BALANCE_DETAIL_PAY',0); //余额记录类型-买单
!defined('SHOP_BALANCE_DETAIL_RECHARGE') && define('SHOP_BALANCE_DETAIL_RECHARGE',1); //余额记录类型-充值
!defined('SHOP_BALANCE_DETAIL_WITHDRAW') && define('SHOP_BALANCE_DETAIL_WITHDRAW',2); //余额记录类型-商家提现

!defined('SHOP_WITHDRAW_PENDING') && define('SHOP_WITHDRAW_PENDING',0); //商家提现状态-待打款
!defined('SHOP_WITHDRAW_SUCCESS') && define('SHOP_WITHDRAW_SUCCESS',1); //商家提现状态-打款成功
!defined('SHOP_WITHDRAW_FAILED') && define('SHOP_WITHDRAW_FAILED',2); //商家提现状态-打款失败

!defined('SELF_PERCENT') && define('SELF_PERCENT',0);//买单消费送魔豆比例
!defined('PARENT_PERCENT') && define('PARENT_PERCENT',0);//上级送魔豆(邀请人)
!defined('PARTNER_PERCENT') && define('PARTNER_PERCENT',0);//合伙人送魔豆

