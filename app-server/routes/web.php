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

/*Route::get('/', function () {
    return view('welcome');
});*/

//分享注册
Route::get('/invitation/{id}','Web\InvitationController@index');

//待审核店铺列表
Route::get('/shops','Web\ShopController@index');
Route::get('/shop/{id}','Web\ShopController@shop');
Route::get('/invitation/{id}','Web\InvitationController@index');
Route::any('{all}', 'WelcomeController@index')->where('all', '.*');
