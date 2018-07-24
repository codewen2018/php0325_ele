<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function add(Request $request){

        if ($request->isMethod("post")){


            $file=$request->file('img');

            if ($file!==null){

                //上传文件
               $fileName= $file->store("test","oss");

               dd(env("ALIYUN_OSS_URL").$fileName);

            }
        }

        return view("shop.test.add");

    }
}
