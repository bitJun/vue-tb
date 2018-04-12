<?php

Route::group(['prefix'=>'test'],function (){
    Route::get('testOrderSettled','Web\TestController@testOrderSettled');//testOrderSettled
    Route::get('gaode','Web\TestController@getGaodeInfo');
    Route::get('system_create','Web\TestController@systemCreate');
    Route::get('short_message','Web\TestController@testMessage');
    Route::get('uploadunitphoto', 'Web\TestController@uploadunitphoto');
});
Route::get('login/captcha.png', 'Web\Utils\CaptchaController@loginCaptcha');
Route::post('auth/login.json', 'Web\Auth\AuthController@login');
Route::group(['prefix'=>'jwt.auth'],function (){
    Route::post('auth/logout.json', 'Web\Auth\AuthController@logout');
});
Route::any('{all}', 'WelcomeController@index')->where('all', '.*');
