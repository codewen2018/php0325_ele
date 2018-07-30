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



    //地址管理
    Route::post("address/add","AddressController@add");

    //购物车
    Route::post("cart/add","CartController@add");
});