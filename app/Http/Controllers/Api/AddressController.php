<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AddressController extends BaseController
{
    /**
     * 地址列表
     */
    public function index(Request $request){
        //得到当前用户ID
        $memberId=$request->input('user_id');
        //返回当前用户所有地址
        $addresses=Address::all();

        //直接返回
        return $addresses;


    }

    public function add(Request $request){

        //验证
       $validate= Validator::make($request->all(),[
           'name'=>"required",
            'tel' => [
                'required',
                'regex:/^0?(13|14|15|17|18|19)[0-9]{9}$/',
            ]

        ]);
        //判断验证
        if ($validate->fails()){

            //返回错误
            return [
                'status'=>"false",
                'message'=>$validate->errors()->first()
            ];

        }

        $data=$request->all();
        $data['is_default']=0;
        //数据入库
       Address::create($data);

        //返回数据

        return [
            'status'=>"true",
            'message'=>"添加成功"
        ];

    }
}
