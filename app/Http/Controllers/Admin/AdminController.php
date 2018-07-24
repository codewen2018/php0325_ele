<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    /**
     * 平台admin登录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function login(Request $request)
    {


        //判断是否POST提交
        if ($request->isMethod("post")) {

            //验证
            $this->validate($request, [
                'name' => "required",
                'password' => "required"
            ]);
            //验证账号密码
            if (Auth::guard("admin")->attempt(['name' => $request->post('name'), 'password' => $request->password])) {
                // session()->flash("success","登录成功");
                //登录成功
                return redirect()->route("admin.shop.index")->with("success", "登录成功");

            } else {
                return redirect()->route("admin.login")->with("danger", "账号或密码错误");
            }

        }
        return view("admin.admin.login");
    }

    /**
     * 更改密码
     */
    public function changePassword(Request $request)
    {
        //判断是否POST提交
        if ($request->isMethod("post")){

            //1.验证
            $this->validate($request,[
                'old_password'=>'required',
                'password'=>'required|confirmed'
            ]);
            //2.得到当前用户对象
            $admin=Auth::guard('admin')->user();

            $oldPassword=$request->post('old_password');
            //3.判断老密码是否正确
            if (Hash::check($oldPassword,$admin->password)){

                //3.1如果老密码正确 设置新密码
                $admin->password=Hash::make($request->post('password'));
                //3.2 保存修改
                $admin->save();

                //3.3 跳转
                return redirect()->route('admin.index')->with("success","修改密码成功");
            }

            //4.老密码不正确
            return back()->with("danger","老密码不正确");


        }
        //显示视图
        return view("admin.admin.change_password");

    }

    /**
     * 平台admin列表
     */
    public function index()
    {
        $admins=Admin::all();

        return view('admin.admin.index',compact('admins'));
    }

    /**
     * 删除平台管理员
     * @param $id
     */
    public function del($id)
    {


        //1号管理员不能删除
        if ($id==1){
            return back()->with("danger","1不能删除");
        }
        $admin=Admin::findOrFail($id);

        $admin->delete();

        return redirect()->route('admin.index')->with("success","删除成功");
    }
    /**
     * 平台admin注销
     */
    public function logout()
    {
        //注销
        Auth::guard('admin')->logout();
        //跳转并设置成功提示
        return redirect()->route("admin.login")->with("success", "成功退出");

    }
}
