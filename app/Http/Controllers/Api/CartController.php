<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends BaseController
{
    /**
     * 购物车列表
     */
    public function index(Request $request)
    {

        /*
        {
             "goods_list": [{
             "goods_id": "1",
         "goods_name": "汉堡",
         "goods_img": "http://www.homework.com/images/slider-pic2.jpeg",
         "amount": 6,
         "goods_price": 10
       },{
             "goods_id": "1",
         "goods_name": "汉堡",
         "goods_img": "http://www.homework.com/images/slider-pic2.jpeg",
         "amount": 6,
         "goods_price": 10
       }],
      "totalCost": 120
     }
        */


       /* $data = [
            "goods_list" => [
                [
                    "goods_id" => "1",
                    "goods_name" => "汉堡",
                    "goods_img" => "http://www.homework.com/images/slider-pic2.jpeg",
                    "amount" => 6,
                    "goods_price" => 10
                ], [
                    "goods_id" => "1",
                    "goods_name" => "汉堡",
                    "goods_img" => "http://www.homework.com/images/slider-pic2.jpeg",
                    "amount" => 6,
                    "goods_price" => 10
                ]],
            "totalCost" => 120];

        return $data;*/

        //用户Id
        $userId = $request->input('user_id');

        //购物车列表
        $carts = Cart::where('user_id', $userId)->get();

        //声明一个数组
        $goodsList = [];
        //声明总价
        $totalCost = 0;
        //循环购物车
        foreach ($carts as $k => $v) {
            $good = Menu::where('id', $v->goods_id)->first(['id as goods_id','goods_name', 'goods_img', 'goods_price']);

            /*
             *
             *   [
                    "goods_id" => "1",
                    "goods_name" => "汉堡",
                    "goods_img" => "http://www.homework.com/images/slider-pic2.jpeg",
                    "amount" => 6,
                    "goods_price" => 10
                ]
             */
            //$good->goods_id = (string)$v->goods_id;
            $good->amount = $v->amount;

            //算总价
            $totalCost += $good->amount * $good->goods_price;
            $goodsList[] = $good;
            //var_dump($good->toArray());

        }

        return [
            'goods_list' => $goodsList,
            'totalCost' => $totalCost
        ];
    }

    /**
     * 添加购物车
     * @param Request $request
     * @return array
     */
    public function add(Request $request)
    {


        //验证
        //清空当前用户购物车
        Cart::where("user_id", $request->post('user_id'))->delete();
        //接收参数
        $goods = $request->post('goodsList');//[2,3,4]
        $counts = $request->post('goodsCount');//[4,2,1]

        foreach ($goods as $k => $good) {
            $data = [
                'user_id' => $request->post('user_id'),
                'goods_id' => $good,
                'amount' => $counts[$k]
            ];
            Cart::create($data);
        }
        return [
            'status' => "true",
            'message' => "添加成功"
        ];

    }
}
