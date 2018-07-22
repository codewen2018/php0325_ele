<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends BaseController
{
    /**
     * 商家列表
     */
    public function index()
    {
        //得到所有商家
        $shops=Shop::all();

        return view('admin.shop.index',compact('shops'));
    }

    //通过审核
    public function changeStatus($id){

        $shop=Shop::findOrFail($id);

        $shop->status=1;

        $shop->save();

        //session()->flash("success","通过审核");
        return back()->with("success","通过审核");

    }
}
