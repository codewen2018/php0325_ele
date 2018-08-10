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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::namespace('Api')->group(function () {
    // 在 "App\Http\Controllers\Api" 命名空间下的控制器
    //店铺列表
    Route::get("shop/list","ShopController@list");
    Route::get("shop/index","ShopController@index");

    //会员管理
    Route::get("member/reg","MemberController@reg");
    Route::get("member/sms","MemberController@sms");
    Route::any("member/login","MemberController@login");
    Route::post("member/reg","MemberController@reg");
    Route::get("member/detail","MemberController@detail");
    Route::get("member/money","MemberController@money");



    //地址管理
    Route::post("address/add","AddressController@add");
    Route::get("address/index","AddressController@index");

    //购物车
    Route::post("cart/add","CartController@add");
    Route::get("cart/index","CartController@index");

    //订单
    Route::post("order/add","OrderController@add");
    Route::get("order/detail","OrderController@detail");
    Route::post("order/pay","OrderController@pay");
    Route::get("order/index","OrderController@index");
    Route::get("order/wxPay","OrderController@wxPay");
    Route::get("order/status","OrderController@status");
    Route::any("order/ok","OrderController@ok");


});