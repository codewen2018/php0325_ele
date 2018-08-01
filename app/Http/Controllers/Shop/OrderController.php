<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{

    public function index()
    {

    }

    public function day(Request $request)
    {
        $query = Order::where("shop_id", 19)->Select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as day,SUM(total) AS money,count(*) AS count"))->groupBy("day")->orderBy("day", 'desc')->limit(30);
        //接收参数
        $start = $request->input('start');
        $end = $request->input('end');

       // var_dump($start,$end);
        //如果有起始时间
        if ($start !== null) {
            $query->whereDate("created_at", ">=", $start);
        }
        
        if ($end !== null) {
            $query->whereDate("created_at", "<=", $end);
        }
        //得到每日统计数据
        $orders=$query->get();
        //dd($orders->toArray());
        //显示视图
        return view('shop.order.day', compact('orders'));
    }


}
