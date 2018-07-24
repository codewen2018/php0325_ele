<?php

namespace App\Http\Controllers\Shop;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends Controller
{
    /**
     * 分类列表
     */
    public function index()
    {
        $cates = MenuCategory::all();

        return view('shop.menu_category.index', compact('cates'));

    }

    /**
     * 添加分类
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')){

            //验证
            $this->validate($request,[
               'name'=>'required',
               'description'=>'required',
               'is_selected'=>'required',
            ]);
            //name不能在本店铺重复
            //当前用户
            //得到shop_id
            $shopId=Auth::user()->shop_id;
            //得到重名的个数
            $count=MenuCategory::where("shop_id",$shopId)->where('name',$request->post('name'))->count();

            //判断
            if ($count){
                //如果存在 返回
                return back()->with('danger',"已存在相同的名称")->withInput();
            }

           //接收参数
            $data=$request->all();
            $data['shop_id']=$shopId;
            //dd($data);

            //判断
            if ($request->post('is_selected')){
                //把表里所的is_selected设置0
                MenuCategory::where("is_selected",1)->where('shop_id',$shopId)->update(['is_selected'=>0]);

            }
            //入库
            MenuCategory::create($data);
            //跳转并提示
            return redirect()->route('menu_cate.index')->with('success',"添加成功");
        }

        //显示视图
        return view('shop.menu_category.add');

    }

    public function edit()
    {

    }

    public function del()
    {

    }
}
