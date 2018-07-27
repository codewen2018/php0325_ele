<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $acts=Activity::all();

        return view('admin.activity.index',compact('acts'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')){

            //验证
            $this->validate($request->all(),[
               'title'=>"required",
               'content'=>"required",
               'start_time'=>"required",
               'end_time'=>"required",
            ]);

            //添加数据
            Activity::create($request->post());
            //返回并提醒
            return redirect()->route("admin.activity.index")->with("success","添加成功");

        }

        return view('admin.activity.add');

    }
}
