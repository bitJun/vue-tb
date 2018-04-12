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

Route::get('login/captcha.png', 'Web\Utils\CaptchaController@loginCaptcha');
Route::post('auth/login.json', 'Web\Auth\AuthController@login');


Route::group(['middleware' => 'jwt.auth'],function (){
    Route::post('auth/logout.json', 'Web\Auth\AuthController@logout');
});
Route::any('{all}', 'WelcomeController@index')->where('all', '.*');
