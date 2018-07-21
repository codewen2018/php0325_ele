<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * 注册
     */
    public function reg(Request $request)
    {

        //判断是不是POST提交
        if ($request->isMethod("post")){
            //dd($request);

           //验证
           $this->validate($request,[
                'shop_cate_id' => 'required|integer',
                'shop_name' => 'required|max:100|unique:shops',
                'shop_img' => 'image|required',
                'start_send' => 'required|numeric',
                'send_cost' => 'required|numeric',
                'notice' => 'string',
                'discount' => 'string',
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required|string|min:6|max:32|confirmed'
            ]);
            //更新店铺表
            $shop=new Shop();
            //设置店铺的状态为0 未审核
            $shop->status=0;
            $shop->shop_img="";
            //批量赋值
            $shop->fill($request->input());
            //图片上传
            $file=$request->file('shop_img');
            //判断是否上传了图片
            if ($file){
                //存在就上传
               $shop->shop_img=$file->store("/upload/images/shop",'images');

               //dd($shop->shop_img);
            }

            //开启事务
            DB::transaction(function () use ($shop,$request){

                //保存商家信息
                $shop->save();
                //Shop::create($request->input());
                //添加用户信息

                User::create([
                    'email'=>$request->input('email'),
                    'shop_id'=>$shop->id,
                    'password'=>bcrypt($request->input('password')),
                    'name'=>$request->input('name'),
                    'email'=>$request->input('email'),
                    'status'=>1
                ]);
            });


            //添加失败
            session()->flash('success','注册成功');
            //跳转至添加页面
            return redirect()->route("user.login");

        }

        //得到所有商家分类
        $cates=ShopCategory::where("status",1)->get();
        //显示视图并赋值
        return view("shop.user.reg",compact('cates'));
    }


    public function login(){
        return 111;
    }
    public function index()
    {

        return view("shop.user.index");
    }
}
