<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Memeber;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderGood;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /**
     * 添加订单
     */
    public function add(Request $request)
    {

        //1.查出收货地址
        $address = Address::find($request->post('address_id'));
        //2.判断地址是否有误
        if ($address === null) {
            return [
                "status" => "false",
                "message" => "地址选择不正确"
            ];
        }

        //3.分别赋值
        //3.1用户Id
        $data['user_id'] = $request->post('user_id');
        //3.2 店铺Id

        $carts = Cart::where("user_id", $request->post('user_id'))->get();
        //先找购物车第一条数据的商品ID，再通过商品ID在菜品中找出shop_id
        $shopId = Menu::find($carts[0]->goods_id)->shop_id;

        $data['shop_id'] = $shopId;

        //3.3 订单号生成 180731094120
        $data['sn'] = date("ymdHis") . rand(1000, 9999);
        //3.4 地址
        $data['provence'] = $address->provence;
        $data['city'] = $address->city;
        $data['area'] = $address->area;
        $data['detail_address'] = $address->detail_address;
        $data['tel'] = $address->tel;
        $data['name'] = $address->name;

        //3.5 算总价
        $total = 0;

        foreach ($carts as $k => $v) {
            $good = Menu::where('id', $v->goods_id)->first();


            //算总价
            $total += $v->amount * $good->goods_price;
        }
        $data['total'] = $total;
        //3.6 状态 等待支付
        $data['status'] = 0;

        //事务启动
        DB::beginTransaction();

        try {

            //3.7 创建订单
            $order = Order::create($data);
            //4.添加订单商品
            foreach ($carts as $k1 => $v1) {

                //找出当前商品
                $good = Menu::find($v1->goods_id);

                //库存不够
                if ($v1->amount > $good->store) {
                    throw new  \Exception($good->goods_name . "库存不足");
                }
                //减库存
                $good->store = $good->store - $v1->amount;
                $good->save();

                //构造数据
                $dataGoods['order_id'] = $order->id;
                $dataGoods['goods_id'] = $v1->goods_id;
                $dataGoods['amount'] = $v1->amount;
                $dataGoods['goods_name'] = $good->goods_name;
                $dataGoods['goods_img'] = $good->goods_img;
                $dataGoods['goods_price'] = $good->goods_price;

                //数据入库
                OrderGood::create($dataGoods);

            }
            //提交
            DB::commit();
        } catch (\Exception $exception) {
            //回滚
            DB::rollBack();
            //返回数据
            return [
                "status" => "false",
                "message" => $exception->getMessage(),
            ];
        } catch (QueryException $exception) {
            //回滚
            DB::rollBack();
            //返回数据
            return [
                "status" => "false",
                "message" => $exception->getMessage(),
            ];
        }

        return [
            "status" => "true",
            "message" => "添加成功",
            "order_id" => $order->id
        ];

        // dump($order->toArray());

    }

    /**
     * 订单详情
     */
    public function detail(Request $request)
    {

        $order = Order::find($request->input('id'));

        $data['id'] = $order->id;
        $data['order_code'] = $order->sn;
        $data['order_birth_time'] = (string)$order->created_at;
        $data['order_status'] = $order->order_status;
        $data['shop_id'] = $order->shop_id;
        $data['shop_name'] = $order->shop->shop_name;
        $data['shop_img'] = $order->shop->shop_img;
        $data['order_price'] = $order->total;
        $data['order_address'] = $order->provence . $order->city . $order->area . $order->detail_address;

        $data['goods_list'] = $order->goods;


        return $data;
//        dump($data);


    }

    /**
     * 订单支付
     */
    public function pay(Request $request)
    {
        // 得到订单
        $order = Order::find($request->post('id'));

        //得到用户
        $member = Memeber::find($order->user_id);

        //判断钱够不够
        if ($order->total > $member->money) {

            return [
                'status' => 'false',
                "message" => "用户余额不够，请充值"
            ];
        }

        //否则扣钱
        $member->money = $member->money - $order->total;
        $member->save();

        //更改订单状态
        $order->status = 1;
        $order->save();

        return [
            'status' => 'true',
            "message" => "支付成功"
        ];
    }

    /**
     * 订单列表
     */
    public function index(Request $request)
    {

        $orders = Order::where("user_id", $request->input('user_id'))->get();

        $datas=[];
        foreach ($orders as $order) {
            $data['id'] = $order->id;
            $data['order_code'] = $order->sn;
            $data['order_birth_time'] = (string)$order->created_at;
            $data['order_status'] = $order->order_status;
            $data['shop_id'] = (string)$order->shop_id;
            $data['shop_name'] = $order->shop->shop_name;
            $data['shop_img'] = $order->shop->shop_img;
            $data['order_price'] = $order->total;
            $data['order_address'] = $order->provence . $order->city . $order->area . $order->detail_address;

            $data['goods_list'] = $order->goods;

            $datas[] = $data;
        }

        return $datas;
    }
}
