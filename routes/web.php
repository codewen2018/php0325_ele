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

return view('index');

});
Route::get('/order/clear', function () {
//处理超时未支付的订单
    /**
     * 1.找出 超时   未支付   订单
     * 当前时间-创建时间>15*60
     * 当前时间-15*60>创建时间
     * 创建时间<当前时间-15*60
     * */
    while (true){
        $orders=\App\Models\Order::where("status",0)->where('created_at','<',date("Y-m-d H:i:s",(time()-15*60)))->update(['status'=>-1]);
        sleep(5);
    }



});

//平台
Route::domain(env('ADMIN_DOMAIN'))->namespace('Admin')->group(function () {

    //测试
    Route::get('/mail', function () {
        $order = \App\Models\Order::find(26);

        return new \App\Mail\OrderShipped($order);
    });

    Route::get('/mailTo', function () {
        $order = \App\Models\Order::find(26);
        $user = \App\Models\User::find(12);

        return \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\OrderShipped($order));
    });
    //region 后台用户
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
//endregion
    //region 店铺分类
    //店铺分类 App\Http\Controllers\Admin
    Route::get('shop_category/index', "ShopCategoryController@index")->name('shop_cate.index');
    Route::get('shop_category/del/{id}', "ShopCategoryController@del")->name('shop_cate.del');
   //endregion
    //region 店铺管理
    #店铺列表
    Route::get('shop/index', "ShopController@index")->name('admin.shop.index');
    #删除店铺
    Route::get('shop/del/{id}', "ShopController@del")->name('admin.shop.del');
    //通过审核
    Route::get('shop/changeStatus/{id}', "ShopController@changeStatus")->name('admin.shop.changeStatus');
 //endregion
    //region 商家用户
    //商家用户管理
    Route::get('user/index', "UserController@index")->name('admin.user.index');
   //endregion
    //region 活动管理
    Route::get('activity/index', "ActivityController@index")->name('admin.activity.index');
    Route::any('activity/add', "ActivityController@add")->name('admin.activity.add');
  //endregion
    //region 权限管理
    Route::get('per/index', "PerController@index")->name('admin.per.index');
    Route::any('per/add', "PerController@add")->name('admin.per.add');
   //endregion
    //region 角色管理
    Route::get('role/index', "RoleController@index")->name('admin.role.index');
    Route::any('role/add', "RoleController@add")->name('admin.role.add');
    Route::any('role/edit/{id}', "RoleController@edit")->name('admin.role.edit');
    Route::get('role/del', "RoleController@del")->name('admin.role.del');
   //endregion
    //region 菜单管理
    Route::get('nav/index', "NavController@index")->name('admin.nav.index');
    Route::any('nav/add', "NavController@add")->name('admin.nav.add');
    //endregion
    //region 抽奖
    Route::get('event/index', "EventController@index")->name('admin.event.index');
    Route::any('event/add', "EventController@add")->name('admin.event.add');
    Route::get('event/open/{id}', "EventController@open")->name('admin.event.open');
    //endregion
    //region 奖品
    Route::get('prize/index', "EventPrizeController@index")->name('admin.prize.index');
    Route::any('prize/add', "EventPrizeController@add")->name('admin.prize.add');
    //endregion

});


Route::domain('shop.ele.com')->namespace('Shop')->group(function () {
    //测试
    Route::any("test/add", "TestController@add")->name('test.add');
    //region 商户首页
    //商户平台首页
    Route::get("index/index", "IndexController@index")->name('index.index');
//endregion
    //region 菜品分类
    Route::get("menu_cate/index", "MenuCategoryController@index")->name('menu_cate.index');
    Route::any("menu_cate/add", "MenuCategoryController@add")->name('menu_cate.add');
    Route::any("menu_cate/edit/{id}", "MenuCategoryController@edit")->name('menu_cate.edit');
    Route::get("menu_cate/del/{id}", "MenuCategoryController@del")->name('menu_cate.del');

    Route::any("menu_cate/upload", "MenuCategoryController@upload")->name('menu_cate.upload');
//endregion
    //region 菜品管理
    Route::get("menu/index", "MenuController@index")->name('menu.index');
    Route::any("menu/add", "MenuController@add")->name('menu.add');
    Route::any("menu/edit/{id}", "MenuController@edit")->name('menu.edit');
    Route::get("menu/del/{id}", "MenuController@del")->name('menu.del');
    Route::any("menu/upload", "MenuController@upload")->name('menu.upload');
//endregion
    //region 商家用户
    # 商家注册
    Route::any('user/reg', "UserController@reg");
    # 商家登录
    Route::any('user/login', "UserController@login")->name('user.login');
    #商家退出
    Route::get('user/logout', "UserController@logout")->name('user.logout');
    //用户列表
    Route::get('user/index', "UserController@index")->name('user.index');
    //endregion
    //region 订单统计
    Route::get('order/day', "OrderController@day")->name('order.day');
    Route::get('order/index', "OrderController@index")->name('order.index');
    Route::get('order/changeStatus/{id}/{status}', "OrderController@changeStatus")->name('order.changeStatus');
    Route::get('order/detail/{id}', "OrderController@detail")->name('order.detail');
//endregion
    //region 抽奖
    Route::get('event/index', "EventController@index")->name('event.index');
    Route::get('event/sign', "EventController@sign")->name('event.sign');
//endregion
});

