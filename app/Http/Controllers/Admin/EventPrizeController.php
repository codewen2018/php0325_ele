<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventPrize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventPrizeController extends BaseController
{
    //

    public function add(Request $request)
    {


        if ($request->isMethod('post')) {


            $this->validate($request, [

                'name' => 'required',
                'event_id' => 'required'


            ]);
           // dd($request->post());
            EventPrize::create($request->post());

            return redirect()->route('admin.prize.index')->with('success', '添加奖品成功');


        }

        //过滤出活动 已开始报名的不能再添加奖品
        $events = Event::where('start_time', '>', time())->get();
        return view("admin.event_prize.add", compact('events'));


    }
}
