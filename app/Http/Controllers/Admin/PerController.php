<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PerController extends Controller
{
    public function index()
    {
        $pers=Permission::all();

    }

    public function add(){

       //添加一个权限
        //$per=Permission::create(['name'=>'shop_cate.index','guard_name'=>'admin']);

        //1.要得到所有路由
       $routes= Route::getRoutes();
     //  dump($routes);

        //3.声明空数组
        $urls=[];
        //2.过滤出命名空间是 App\Http\Controllers\Admin 路由
        foreach ($routes as $route){


            if ($route->action['namespace']==="App\Http\Controllers\Admin") {
                //dump($route->action['as']);
                //判断是否存在名字
                if (isset($route->action['as'])){
                    $urls[]=$route->action['as'];
                }

            }
        }



        //1.取出现有的所有权限转化成数组
        //2.上面那个数组和当前$urls  array_diff()

        dump($urls);



    }

    public function del(){

    }
}
