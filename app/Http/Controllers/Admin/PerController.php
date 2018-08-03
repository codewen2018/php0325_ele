<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PerController extends Controller
{
    public function index()
    {
        $pers=Permission::all();

    }

    public function add(){

        //添加一个权限
        $per=Permission::create(['name'=>'shop_cate.index','guard_name'=>'admin']);



    }

    public function del(){

    }
}
