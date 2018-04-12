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

Route::group(['middleware' => 'jwt.auth'], function()
{
    Route::get('user.json', 'Api\User\UserController@getCurrentUser');
});

Route::get('qiniu/token.json', 'Api\Utils\QiniuController@qiniuToken');
Route::get('qiniu/callback', 'Api\Utils\QiniuController@qiniuCallback');
Route::post('qiniu/callback', 'Api\Utils\QiniuController@qiniuCallback');
Route::post('qiniu/upload', 'Api\Utils\QiniuController@qiniuUpload');

Route::get('test/{type}/{id}', 'Api\Order\TestController@test');
//商圈
Route::group(['middleware' => ['jwt.auth']],function() {
    Route::get('timelines.json','Api\Timeline\TimelineController@getTimelines');//列表
    Route::post('timeline.json','Api\Timeline\TimelineController@postTimeline');//新增
    Route::put('timeline/{id}.json','Api\Timeline\TimelineController@putTimeline');//编辑
    Route::get('timeline/{id}.json','Api\Timeline\TimelineController@getTimeline');//单条
    Route::delete('timeline/{id}.json','Api\Timeline\TimelineController@deleteTimeline');//删除
});

//订单
Route::group(['middleware' => ['jwt.auth']],function() {
    Route::get('orders.json','Api\Order\OrderController@getOrders');//订单列表
    Route::get('order/{id}.json','Api\Order\OrderController@getOrder');//订单详细
});


//会员
Route::group(['middleware' => ['jwt.auth']],function() {
    Route::get('members.json','Api\Member\ShopMemberController@getMembers');//列表
    Route::get('credit/details.json','Api\Member\CreditDetailController@getDetails');
    Route::get('balance/details.json','Api\Member\BalanceDetailController@getDetails');
    Route::get('member_level.json','Api\Member\MemberLevelController@getMemberLevel');
    Route::post('member_level.json','Api\Member\MemberLevelController@postMemberLevel');
});

//商户信息修改
Route::group(['middleware'=>['jwt.auth']],function (){
    Route::put('shop.json','Api\Shop\ShopController@putShop');
    Route::get('shop.json','Api\Shop\ShopController@getShop');
    Route::post('shop/expand.json','Api\Shop\ShopController@postShopExpand');//添加商户扩展信息
    Route::put('shop/expand.json','Api\Shop\ShopController@putShopExpand');//修改商户扩展信息
    Route::get('shop/expand.json','Api\Shop\ShopController@getShopExpand');//获取商户扩展信息
});

//连连支付设置相关
Route::group(['middleware'=>['jwt.auth'],'prefix'=>'llpay'],function (){
    Route::post('smssend.json','Api\Trade\TradeSettingController@smsSend');//连连支付发送短信接口
    Route::post('smscheck.json','Api\Trade\TradeSettingController@smsCheck');//连连支付验证短信接口
    Route::post('opensmsunituser.json','Api\Trade\TradeSettingController@openSmsUnitUser');//开户认证
    Route::post('uploadunitphoto.json','Api\Trade\TradeSettingController@uploadUnitPhoto');//上传身份证、营业执照等图片
    Route::get('info.json','Api\Trade\TradeSettingController@getLlpayInfo');//获取信息

    //Route::post('bankcardOpenAuth.json','Api\Trade\TradeSettingController@bankcardOpenAuth');//个体工商户绑卡认证接口
    Route::post('bankcardAuthVerfy.json','Api\Shop\BankController@bankcardAuthVerfy');//个体工商户银行卡绑卡验证

    Route::post('pwdauth.json','Api\Trade\TradeSettingController@pwdAuth');//支付密码验证授权接口 用于更新连连支付相关信息
    Route::put('modifyunituser.json','Api\Trade\TradeSettingController@modifyUnitUser');//修改基本信息
    Route::put('modiyunituseracct.json','Api\Trade\TradeSettingController@modifyUnitUserAcct');//修改银行卡信息，对公账户
    Route::get('single_user.json','Api\Trade\TradeSettingController@getSingleUser');//查询子账号信息
});

//商家提现
Route::group(['middleware' => 'jwt.auth'],function(){
    Route::get('bank_cards.json','Api\Shop\BankController@getBanks');   //获取银行卡列表
    Route::get('bank_card/{id}.json','Api\Shop\BankController@getBankById');   //获取银行卡信息
    Route::post('bank_card.json','Api\Shop\BankController@postBank');   //添加银行卡
    Route::put('bank_card/{id}.json','Api\Shop\BankController@putBank');   //修改银行卡
    Route::delete('bank_card/{id}.json','Api\Shop\BankController@deleteBank');   //删除银行卡
});

//魔豆设置
Route::group(['middleware'=>['jwt.auth']],function (){
    Route::get('credit_rule.json','Api\Credit\CreditRuleController@getCreditRule');//读取魔豆设置
    Route::put('credit_rule.json','Api\Credit\CreditRuleController@putCreditRule');//更新魔豆设置
});

//用户设置
Route::group(['middleware'=>['jwt.auth']],function (){
    Route::put('password.json','Api\User\UserController@putPassword');//更新密码
});

//提现
Route::group(['middleware'=>['jwt.auth']],function (){
    Route::get('shop/withdraw_status.json','Api\Shop\WithdrawController@getWithdrawStatus');
    Route::get('shop/withdraws.json','Api\Shop\WithdrawController@getWithdraws');//提现列表
    Route::post('shop/withdraw.json','Api\Shop\WithdrawController@postWithdraw');//申请提现
});

//收支明细
Route::group(['middleware'=>['jwt.auth']],function () {
    Route::get('shop/balance_details.json','Api\Shop\BalanceDetailController@getBalanceDetails');
});

//二维码
Route::group(['middleware'=>['jwt.auth']],function (){
    Route::get('shop/qrcodes.json', 'Api\Utils\QrcodeController@getQrcodes');
});

Route::get('regions.json', 'Api\Utils\RegionController@getRegionsByTree');
Route::get('banks.json', 'Api\Utils\BankController@getBanks');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});