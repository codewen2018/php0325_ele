<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopCategoryController extends BaseController
{
    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $cates=ShopCategory::all();

        return view("admin.shop_category.index",compact('cates'));
    }

    public function del($id){
        //得到当前分类
        $cate=ShopCategory::findOrFail($id);

        //得到当前分类对应的店铺数
        $shopCount=Shop::where('shop_cate_id',$cate->id)->count();
        //判断当前分类店铺数

        if ($shopCount){
            //回跳
            return  back()->with("danger","当前分类下有店铺，不能删除");
        }

        //否则删除
        $cate->delete();
        //跳转
        return redirect()->route('shop_cate.index')->with('success',"删除成功");

    }
}
