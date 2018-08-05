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

Route::get('/', function () {
    return "home";
});
//平台
Route::domain('admin.zhilipeng.com')->namespace('Admin')->group(function () {

    //测试
    Route::get('/mail', function () {
        $order =\App\Models\Order::find(26);

        return new \App\Mail\OrderShipped($order);
    });
    #后台用户登录
    Route::any('admin/login', "AdminController@login")->name('admin.login');
    #后台用户退出
    Route::get('admin/logout', "AdminController@logout")->name('admin.logout');
    #用户更改密码
    Route::any('admin/changePassword', "AdminController@changePassword")->name('admin.changePassword');
    #后台用户列表
    Route::get('admin/index', "AdminController@index")->name('admin.index');
    #后台用户添加
    Route::any('admin/add', "AdminController@add")->name('admin.add');
    #后台用户删除
    Route::get('admin/del/{id}', "AdminController@del")->name('admin.del');

    //店铺分类 App\Http\Controllers\Admin
    Route::get('shop_category/index', "ShopCategoryController@index")->name('shop_cate.index');
    Route::get('shop_category/del/{id}', "ShopCategoryController@del")->name('shop_cate.del');

    //店铺管理
    #店铺列表
    Route::get('shop/index', "ShopController@index")->name('admin.shop.index');
    #删除店铺
    Route::get('shop/del/{id}', "ShopController@del")->name('admin.shop.del');
    //通过审核
    Route::get('shop/changeStatus/{id}', "ShopController@changeStatus")->name('admin.shop.changeStatus');


    //商家用户管理
    Route::get('user/index', "UserController@index")->name('admin.user.index');


    //活动管理
    Route::get('activity/index', "ActivityController@index")->name('admin.activity.index');
    Route::any('activity/add', "ActivityController@add")->name('admin.activity.add');


    //权限管理
    Route::get('per/index', "PerController@index")->name('admin.per.index');
    Route::any('per/add', "PerController@add")->name('admin.per.add');

    //角色管理
    Route::get('role/index', "RoleController@index")->name('admin.role.index');
    Route::any('role/add', "RoleController@add")->name('admin.role.add');
    Route::any('role/edit/{id}', "RoleController@edit")->name('admin.role.edit');
    Route::get('role/del', "RoleController@del")->name('admin.role.del');


    //菜单管理
    Route::get('nav/index', "NavController@index")->name('admin.nav.index');
    Route::any('nav/add', "NavController@add")->name('admin.nav.add');
});

//商户
Route::domain('shop.zhilipeng.com')->namespace('Shop')->group(function () {

    //测试
    Route::any("test/add","TestController@add")->name('test.add');

    //商户平台首页
    Route::get("index/index","IndexController@index")->name('index.index');


    //菜品分类
    Route::get("menu_cate/index","MenuCategoryController@index")->name('menu_cate.index');
    Route::any("menu_cate/add","MenuCategoryController@add")->name('menu_cate.add');
    Route::any("menu_cate/edit/{id}","MenuCategoryController@edit")->name('menu_cate.edit');
    Route::get("menu_cate/del/{id}","MenuCategoryController@del")->name('menu_cate.del');

    Route::any("menu_cate/upload","MenuCategoryController@upload")->name('menu_cate.upload');

    //菜品管理
    Route::get("menu/index","MenuController@index")->name('menu.index');
    Route::any("menu/add","MenuController@add")->name('menu.add');
    Route::any("menu/edit/{id}","MenuController@edit")->name('menu.edit');
    Route::get("menu/del/{id}","MenuController@del")->name('menu.del');
    Route::any("menu/upload","MenuController@upload")->name('menu.upload');

    # 商家注册
    Route::any('user/reg', "UserController@reg");
    # 商家登录
    Route::any('user/login', "UserController@login")->name('user.login');
    #商家退出
    Route::get('user/logout', "UserController@logout")->name('user.logout');
    //商家首页
    Route::get('user/index', "UserController@index")->name('user.index');

    //订单统计
    Route::get('order/day', "OrderController@day")->name('order.day');
    Route::get('order/index', "OrderController@index")->name('order.index');
    Route::get('order/changeStatus/{id}/{status}', "OrderController@changeStatus")->name('order.changeStatus');
    Route::get('order/detail/{id}', "OrderController@detail")->name('order.detail');

});

