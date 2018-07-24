<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseController
{

    /**
     * 菜品列表
     */
    public function index()
    {

        //接收参数
        $minPrice = \request()->input('minPrice');
        $maxPrice = \request()->input('maxPrice');
        $keyword = \request()->input('keyword');
        $cateId = \request()->input('cate_id');
        // $query=DB::table('menus');
        $query = Menu::orderBy('id');


        if ($minPrice !== null) {

            $query->where('goods_price', '>=', $minPrice);
        }


        if ($maxPrice !== null) {

            $query->where('goods_price', '<=', $maxPrice);
        }

        if ($keyword !== null) {

            $query->where('goods_name', 'like', "%{$keyword}%");

        }
        if ($cateId !== null) {

            $query->where('goods_name', 'like', "%{$cateId}%");

        }


        $menus = $query->paginate(1);

        //得到所有分类
        $cates = MenuCategory::all();

        return view('shop.menu.index', compact('menus', 'cates'));


    }

    public function add(Request $request)
    {

        if ($request->isMethod("post")) {

            //验证


            //接收参数
            $data=$request->post();
            $data['shop_id']=Auth::user()->shop_id;
            //入库
            Menu::create($data);

            //提示成功
            return redirect()->route('menu.index')->with("success","添加成功");

        }
        $cates = MenuCategory::all();
        return view("shop.menu.add", compact('cates'));
    }

    public function edit()
    {

    }

    public function del()
    {

    }

    /**
     *文件上传处理
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {

        //接收input中的name的值是file
        $file=$request->file("file");
        if ($file!==null){
            $fileName = $request->file('file')->store("menu", "oss");
            $data = [
                'status' => 1,
                'url' => env("ALIYUN_OSS_URL").$fileName
            ];

        }else{
            $data = [
                'status' => 0,
                'url' => ""
            ];
        }


        return $data;
    }
}
