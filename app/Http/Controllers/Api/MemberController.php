<?php

namespace App\Http\Controllers\Api;

use App\Models\Memeber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Mrgoon\AliSms\AliSms;

class MemberController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin:*');
    }

    /**
     * 发送短信
     */
    public function sms()
    {

        //1.接收参数 手机号
        $tel = \request()->input('tel');

        //判断一下是不是一个合格手机号
        //2.生成一个验证码
        $code = rand(1000, 9999);
        //3.把验证码存起来  存在哪里？  如何存?
        //3.1 存在Redis里

        //3.2 如何存  键  ===》值      ["tel_18544445555"=>1245];  只能存5分钟
        /*Redis::set("tel_".$tel,$code);//存进来
        Redis::expire("tel_".$tel,300);//设置过期时间*/

        //优先使用Session，其次缓存，再数据库  大型网站使用redis
        Redis::setex("tel_" . $tel, 300, $code);

        //4.测试
        //配置

        return [
            "status" => "true",
            "message" => "获取短信验证码成功" . $code
        ];
        $config = [
            'access_key' => 'LTAIrGYffYL2khhY',
            'access_secret' => 'J9LzDSH0R0WzbICjKzmV257xZmcP26',
            'sign_name' => '杜连杰',
        ];


        $aliSms = new AliSms();
        //调用接口发送短信
        $response = $aliSms->sendSms($tel, 'SMS_140690138', ['code' => $code], $config);


        if ($response->Message === "OK") {
            //成功

            return [
                "status" => "true",
                "message" => "获取短信验证码成功"
            ];
        } else {
            //失败
            return [
                "status" => "false",
                "message" => $response->Message
            ];
        }
    }

    /**
     * 用户注册
     */
    public function reg(Request $request)
    {

            //接收参数
            $data = $request->all();
            //创建一个验证规则
            $validate = Validator::make($data, [
                'username' => 'required|unique:memebers',
                'sms' => 'required|integer|min:1000|max:9999',
                'tel' => [
                    'required',
                    'regex:/^0?(13|14|15|17|18|19)[0-9]{9}$/',
                    'unique:memebers'
                ],
                'password' => 'required|min:6'
            ]);
            //验证 如果有错
            if ($validate->fails()) {

                //返回错误
                return [
                    'status' => "false",
                    //获取错误信息
                    "message" => $validate->errors()->first()
                ];

            }

            //验证 验证码
            //1.取出验证码
            $code = Redis::get("tel_" . $data['tel']);
            //2.判断验证码是否和取出的一致
            if ($code != $data['sms']) {
                //返回错误
                return [
                    'status' => "false",
                    //获取错误信息
                    "message" => "验证码错误"
                ];

            }
            //密码加密
          //  $data['password'] = bcrypt($data['password']);
            $data['password'] =Hash::make($data['password']);
            //数据入库
            Memeber::create($data);
            //返回数据
            return [
                'status' => "true",
                "message" => "添加成功"
            ];




    }

    /**
     * 登录
     */
    public function login(Request $request)
    {

        //1.先通过用户名找哪当前用户
        $member = Memeber::where("username", $request->post('name'))->first();

        //2.如果用户密码存在 再来验证密码  Hash:check
        //3.如果密码也成功 登录成功
        if ($member && Hash::check($request->post('password'), $member->password)) {
            return [
                'status' => 'true',
                'message' => '登录成功',
                'user_id'=>$member->id,
                'username'=>$member->username
            ];

        }

        return [
            'status' => 'false',
            'message' => '账号或密码错误'
        ];


    }
}
