<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderGood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{
    public function index(){

        //1.得到店铺Id
        $shopId=Auth::user()->shop_id;
        //2.今日订单数
        $ordersDay=Order::where("shop_id",$shopId)->whereDate("created_at",date("Y-m-d"))->count();
        //4.今日收入
        $todayMoney=Order::where("shop_id",$shopId)->whereDate("created_at",date("Y-m-d"))->sum('total');
        //3.总订单数
        $ordersCount=Order::where("shop_id",$shopId)->count();

        //5.总结收入
        $allMoney=Order::where("shop_id",$shopId)->sum('total');

        return view("shop.index.index",compact('ordersDay','ordersCount','todayMoney','allMoney'));
    }
}
