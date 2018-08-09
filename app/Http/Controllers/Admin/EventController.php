<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventPrize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('admin.event.index', compact('events'));
    }

    public function add(Request $request)
    {

        if ($request->isMethod('post')) {

            $this->validate($request, [
                'title' => 'required',
                'num' => 'required',
            ]);

            $data = $request->post();
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
            $data['prize_time'] = strtotime($data['prize_time']);
            $data['is_prize'] = 0;

            $event=Event::create($data);

            //1.把活动人数添加到redis中
            Redis::set("event_num:".$event->id,$event->num);

            return redirect()->route('admin.event.index')->with("添加活动成功");


        }
        return view('admin.event.add');
    }
    //活动开奖
    public function open(Request $request,$id){
        //1.通过当前活动ID把已经报名的用户ID取出来
        $userIds=DB::table('event_users')->where('event_id',$id)->pluck('user_id')->toArray();

        //2.打乱$userIds
       shuffle($userIds);

       //3.找出当前活动的奖品 并随机打乱
        $prizes=EventPrize::where("event_id",$id)->get()->shuffle();

        //4.操作奖品表
        foreach ($prizes as $k=>$prize){

           //4.1 给奖品的user_id 赋值
            $prize->user_id=$userIds[$k];
            //4.2 保存修改状态
            $prize->save();
        }
        //5.修改当前活动状态
        $event=Event::findOrFail($id);
        $event->is_prize=1;
        $event->save();

        //6.返回
        return redirect()->route('admin.event.index')->with('success','开奖成功');


        //用redis开奖思路
        //得到奖品数
        //根据奖品数随机取对应用户出来
       $中奖人= Redis::SRANDMEMBER("event:3","奖品数");

        //活动结束 把redis中的数据同步到数据库中
        $所有报名用户=Redis::SMEMBERS("event:3");
        //再把上面的数据存到 event_users表中



    }
}
