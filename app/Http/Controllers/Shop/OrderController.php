<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{

    /**
     * 订单列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $shopId = Auth::user()->shop_id;

        $orders = Order::where("shop_id", $shopId)->latest()->paginate(3);

        return view("shop.order.index", compact('orders'));
    }

    /**
     * 按日统计
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $orders = $query->get();
        //dd($orders->toArray());
        //显示视图
        return view('shop.order.day', compact('orders'));
    }

    /**
     * 更改状态
     * @param $id 订单号
     * @param $status 状态 -1 取消  0 等待付款
     */
    public function changeStatus($id, $status)
    {

       $result= Order::where("id",$id)->where("shop_id",Auth::user()->shop_id)->update(['status'=>$status]);

       if ($result){
           return redirect()->route('order.index')->with("success","更改状态成功");
       }

    }

    /**
     * 订单详情
     * @param $id
     */
    public function detail($id)
    {

        //orders

        //order_goods

    }

}
