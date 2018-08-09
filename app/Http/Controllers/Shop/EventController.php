<?php

namespace App\Http\Controllers\Shop;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view("shop.event.index", compact('events'));
    }

    public function sign(Request $request)
    {
        $eventId = $request->input('event_id');
        $userId = $request->input('user_id');

        //判断当前报名人数 和限制报名人数

        //1.取出限制报名人数
        $num=Redis::get("event_num:".$eventId);

        //2.取出已报名人数
        $users=Redis::scard("event:".$eventId);

        //3.判断
        if ($users<$num){

            //存reids 集合
            Redis::sadd("event:".$eventId,$userId);
            return "报名成功";
        }

        return "已报满";

    }
}
