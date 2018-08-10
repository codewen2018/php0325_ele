<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Memeber;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderGood;
use App\Models\Shop;
use EasyWeChat\Foundation\Application;


use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;

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

        //3.3 订单号生成 1807310941203456
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

            //清空当前用户购物车
            Cart::where("user_id",$request->post('user_id'))->delete();
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


    //微信支持
    public function wxPay(Request $request){

        //得到订单
        $order=Order::find($request->input('id'));

        //dd(config('wechat'));

        //1.创建操作微信的对象
        $app = new Application(config('wechat'));
        //2.得到支付对象
        $payment = $app->payment;
        //3.生成订单
        //3.1 订单配置
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => '源码点餐',
            'detail'           => '源码点餐',
            'out_trade_no'     => time(),
            'total_fee'        => $order->total*100, // 单位：分
            'notify_url'       => 'http://wenwww.zhilipeng.com/api/order/ok', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
           // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        //3.2 订单生成
        $order = new \EasyWeChat\Payment\Order($attributes);
        //4.统一下单
        $result = $payment->prepare($order);
       // dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            //5.取出预支付链接
           $payUrl=  $result->code_url;
           //6.把支付链接生成二维码
            /*$qrCode = new QrCode($payUrl);
            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();*/

            // Create a basic QR code
            $qrCode = new QrCode($payUrl);//地址
            $qrCode->setSize(200);//二维码大小

// Set advanced options
            $qrCode->setWriterByName('png');
            $qrCode->setMargin(10);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);//容错级别
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
            $qrCode->setLabel('微信扫码支付', 16, public_path().'/assets/noto_sans.otf', LabelAlignment::CENTER);
            $qrCode->setLogoPath(public_path().'/assets/ll.png');
            $qrCode->setLogoWidth(80);//logo大小


// Directly output the QR code
            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();
            exit;
        }


    }

    //微信异步通知方法
    public function ok(){

        //1.创建操作微信的对象
        $app = new Application(config('wechat'));
        //2.处理微信通知信息
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
          //  $order = 查询订单($notify->out_trade_no);
            $order=Order::where("sn",$notify->out_trade_no)->first();

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status!==0) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
               // $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 1;//更新订单状态
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;

    }
    //订单状态
    public function status(Request $request)
    {

        return [
            'status'=>Order::find($request->input('id'))->status
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
