<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OrderShipped;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ShopController extends BaseController
{
    /**
     * 商家列表
     */
    public function index()
    {
        //得到所有商家
        $shops = Shop::all();

        return view('admin.shop.index', compact('shops'));
    }

    //通过审核
    public function changeStatus($id)
    {

        $shop = Shop::findOrFail($id);

        $shop->status = 1;

        $shop->save();

        $order =\App\Models\Order::find(26);

        $user=User::where('shop_id',$id)->first();
        //通过审核发送邮件
        Mail::to($user)->send(new OrderShipped($order));
        //session()->flash("success","通过审核");
        return back()->with("success", "通过审核");

    }

    /**
     * 删除店铺
     */
    public function del($id)
    {
       // dd($id);
        //删除店铺需要同时删除用户 需要用到事务保证
        DB::transaction(function () use ($id) {
            //删除店铺
            $shop = Shop::findOrFail($id)->delete();
            //删除用户
            $user = User::where("shop_id", $id)->delete();
        });

        //跳转
        return redirect()->route("admin.shop.index")->with('success','删除成功');
    }
}
