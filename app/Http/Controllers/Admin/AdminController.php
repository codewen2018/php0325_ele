<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends BaseController
{
    public function login(Request $request){


        //判断是否POST提交
        if ($request->isMethod("post")){

            //验证
            $this->validate($request,[
                'name'=>"required",
                'password'=>"required"
            ]);
            //验证账号密码
            if (Auth::guard('admin')->attempt(['name'=>$request->post('name'),'password'=>$request->password])){
                // session()->flash("success","登录成功");
                //登录成功
                return redirect()->route("admin.shop.index")->with("success","登录成功");

            }else{
                return redirect()->route("admin.login")->with("danger","账号或密码错误");
            }

        }
        return view("admin.admin.login");
    }
}
