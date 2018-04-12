<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/wxpay', function () {
    return view('wxpay');
});*/
/*Route::get('/alipay', function () {
    return view('alipay');
});*/
/*Route::get('/paid', function () {
    return view('paid');
});*/
Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user, session());
    });
});
Route::get('clear','TestController@clear');

Route::get('tpaya', function () {
    return view('paya');
});
Route::get('tpaid', function () {
    return view('paid');
});


Route::get('pay/{token}','Web\Trade\PayController@pay');
Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('wxpay/{token}', 'WelcomeController@index');
});
Route::get('sms/captcha', 'Web\Utils\CaptchaController@captcha');
Route::any('api/order.json','Api\Order\OrderController@createOrder');
Route::post('api/sms/send_verify_code.json', 'Api\MobileSms\MobileSmsController@sendVerifyCode');
Route::post('api/sms/check_captcha.json', 'Api\MobileSms\MobileSmsController@checkCaptcha');

Route::post('api/give_credit.json', 'Api\Credit\GiveCreditController@giveCredit');
Route::post('api/bind_member.json', 'Api\Member\MemberController@bindMember');

Route::any('{all}', 'WelcomeController@index')->where('all', '.*');
