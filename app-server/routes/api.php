<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
  }); */

// 接管路由
$api = app('Dingo\Api\Routing\Router');

// 配置api版本和路由
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {

    $api->post('/auth/login.json', 'Auth\AuthController@login');
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->post('/auth/logout.json', 'Auth\AuthController@logout');
        $api->put('/auth/bind_device_id.json', 'Auth\AuthController@bindDeviceId');

        //消息
        $api->get('/moker/messages.json', 'Message\MokerMessageController@getMokerMessages');

        //推送设置
        $api->get('/moker/setting.json', 'Moker\MokerSettingController@getSetting'); //获取设置信息
        $api->post('/moker/setting.json', 'Moker\MokerSettingController@setSetting'); //保存设置信息
        //收益列表
        $api->get('/moker/commission.json', 'Moker\MokerCommController@getCommission');

        //个人资料
        $api->put('moker.json', 'Moker\MokerController@editMoker');
        //富豪榜
        $api->get('/ranking/{type}.json', 'Moker\MokerCommController@ranking_list');

        //店铺铺列表
        $api->get('shops.json', 'Shop\ShopController@getShops');

        //店铺详情
        $api->get('shop/{id}.json', 'Shop\ShopController@getShop');

        //添加店铺
        $api->post('shop.json', 'Shop\ShopController@postShop');

        //订单 开启魔客支付, 魔客订单统一生成在魔店ID8、order_8表
        $api->post('order.json', 'Order\OrderController@createOrder');
        $api->get('order/{id}.json', 'Order\OrderController@getOrder');
        $api->get('/payments.json', 'Payment\PaymentController@getPayments');

        //邀请展示页信息
        $api->get('invitation/info.json', 'Moker\MokerController@getInvitationInfo');
        //邀请二维码
        $api->get('invitation/qrcode.json', 'Utils\QrcodeController@getInvitationQrcode');

        $api->post('/Withdrawals/WithdrawalsAccounts.json', 'Withdrawals\WithdrawalsController@Withdrawals_accounts'); //提现帐号设置
        $api->post('/Withdrawals/MokerWithdraw.json', 'Withdrawals\WithdrawalsController@MokerWithdraw'); //申请提现
        $api->get('/Withdrawals/WithdrawRecord.json', 'Withdrawals\WithdrawalsController@WithdrawRecord'); //提现记录
        $api->get('/Withdrawals/getaccounts.json', 'Withdrawals\WithdrawalsController@getAccounts'); //获取提现帐号
        $api->put('/Withdrawals/accounts.json', 'Withdrawals\WithdrawalsController@putAccounts'); //修改提现帐号
        $api->get('/moker.json', 'Moker\MokerController@getMoker'); //个人中心获取moker信息
        $api->get('/invitee.json', 'Moker\MokerController@getInvitee'); //获取邀请魔客
        $api->get('/auth/statistics.json', 'Auth\IndexController@getStatistics'); //首页统计数据
        $api->get('/moker_level.json', 'Moker\MokerLevelController@getMokerLevel'); //个人中心获取moker信息
        $api->put('/Withdrawals/examine.json', 'Withdrawals\WithdrawalsController@putExamine'); //提现记录审核
        //获取店铺信息(未审核通过)
        $api->get('shop_apply/{id}.json', 'Shop\ShopController@getShopApply');
        //修改未审核店铺信息
        $api->put('shop_apply/{id}.json','Shop\ShopController@putShopApply');

        //验证修改密码短信
        $api->post('verify.json', ['middleware' => 'web', 'uses' => 'Auth\AuthController@verifysms']);
        //修改密码设置新密码
        $api->post('set_new_pass.json', ['uses' => 'Auth\AuthController@setNewPass']);
    });

    //店铺类目
    $api->get('/tags.json', 'Shop\TagController@getTags'); //获取tag
    //地址
    $api->get('regions.json', 'Utils\RegionController@getRegions');

    //分享注册魔客/APP注册魔客
    $api->post('/moker.json', ['middleware' => 'web', 'uses' => 'Moker\MokerController@postMoker']);

    //获取邀请魔客信息
    $api->get('/moker/{id}.json', ['middleware' => 'web', 'uses' => 'Moker\MokerController@getMokerById']);

    //邀请注册魔客 发送短信
    $api->post('send_reg_message.json', ['middleware' => 'web', 'uses' => 'MobileSms\MobileSmsController@sendRegSms']);
    //发送短信验证码
    $api->post('send_message.json', ['middleware' => 'web', 'uses' => 'MobileSms\MobileSmsController@sendSms']);
    //忘记密码提交接口
    $api->post('set_pass.json',['middleware' => 'web', 'uses' => 'Auth\AuthController@setPass']);
    //图片验证码地址
    $api->get('sms_captcha', ['as' => 'sms_captcha', 'middleware' => 'web', 'uses' => 'Utils\CaptchaController@captcha']);
    //七牛
    $api->get('/qiniu/token.json', 'Utils\QiniuController@qiniuToken');
    $api->get('/qiniu/callback', 'Utils\QiniuController@qiniuCallback');
    $api->post('/qiniu/callback', 'Utils\QiniuController@qiniuCallback');


    //审核店铺通过 后面跟shop_apply的id
    $api->post('audit_shop/{id}.json','Shop\ShopController@auditShop');
});
