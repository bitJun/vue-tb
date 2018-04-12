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

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user.json', 'Api\Partner\PartnerController@getCurrentUser');

    Route::get('partners.json', 'Api\Partner\PartnerController@getPartners');

    //修改密码
    Route::put('pass.json', 'Api\Partner\PartnerController@putPassword');

    //添加商家
    Route::post('shop.json', 'Api\Shop\ShopController@postShop');
    //商家列表
    Route::get('shops.json', 'Api\Shop\ShopController@getShops');
    //商家详细
    Route::get('shop/{id}.json','Api\Shop\ShopController@getShop');
    //修改商家
    Route::put('shop/{id}.json','Api\Shop\ShopController@putShop');

    //服务商详情
    Route::get('facilitator/info.json', 'Api\Facilitator\FacilitatorController@info');
    //下级服务商列表
    Route::get('facilitator/list.json', 'Api\Facilitator\FacilitatorController@partnerList');
    //添加下级服务商
    Route::post('facilitator/store.json', 'Api\Facilitator\FacilitatorController@store');
    //查看服务商详情
    Route::get('facilitator/{id}.json', 'Api\Facilitator\FacilitatorController@show');
    //删除服务商
    Route::delete('facilitator/{id}.json', 'Api\Facilitator\FacilitatorController@destroy');
    //编辑服务商信息
    Route::patch('facilitator/{id}.json', 'Api\Facilitator\FacilitatorController@update');
    //魔客
    Route::get('mokerlist.json', 'Api\Moker\MokerController@getMokerList');
    Route::get('moker/{id}.json', 'Api\Moker\MokerController@getMoker');
    //佣金
    Route::get('commissionlist.json', 'Api\Commission\CommissionController@getCommissionList');
    Route::get('commission/{id}.json', 'Api\Commission\CommissionController@getCommission');
    //提现账号
    Route::post('Withdrawals.json', 'Api\Facilitator\WithdrawalsController@Withdrawals_accounts'); //添加
    Route::get('index.json', 'Api\Commission\CommissionController@Home_page'); //首页
    Route::get('Withdrawals.json', 'Api\Facilitator\WithdrawalsController@getAccounts'); //查询
});
//银行列表
 Route::get('bank.json', 'Api\Facilitator\WithdrawalsController@getBank'); //查询
//店铺类目
Route::get('/tags.json', 'Api\Shop\TagController@getTags'); //获取tag
//地址
Route::get('regions.json', 'Api\Utils\RegionController@getRegions');
//所有地址
Route::get('regions_list.json', 'Api\Utils\RegionController@getRegionsList');

//七牛
Route::get('qiniu/token.json', 'Api\Utils\QiniuController@qiniuToken');
Route::get('qiniu/callback', 'Api\Utils\QiniuController@qiniuCallback');
Route::post('qiniu/callback', 'Api\Utils\QiniuController@qiniuCallback');
Route::post('qiniu/upload', 'Api\Utils\QiniuController@qiniuUpload');
