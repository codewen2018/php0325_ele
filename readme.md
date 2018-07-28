# ELE点餐平台

##  项目介绍

整个系统分为三个不同的网站，分别是 

- 平台：网站管理者 
- 商户：入住平台的餐馆 
- 用户：订餐的用户

## Day01
### 开发任务
#### 平台端 
- 商家分类管理 
- 商家管理 
- 商家审核

#### 商户端 
- 商家注册
#### 要求 
- 商家注册时，同步填写商家信息，商家账号和密码 
- 商家注册后，需要平台审核通过，账号才能使用 
- 平台可以直接添加商家信息和账户，默认已审核通过
### 实现步骤

1. composer create-project --prefer-dist laravel/laravel ele "5.5.*" -vvv

2. 设置虚拟主机  三个域名

3. 把基本配置 

4. 建立数据库ele

5. 配置.env文件 数据库配好

6. 配置语言包

7. 数据迁移

8. 创建表  php artisan make:model Models/ShopCategory -m

9. 准备好基础模板

10. 创建 控制器 php artisan make:controller Admin/ShopCategoryController

11. 创建视图 视图也要分模块

12. 路由需要分组

    ```php
    Route::get('/', function () {
        return view('welcome');
    });
    //平台
    Route::domain('admin.ele.com')->namespace('Admin')->group(function () {
        //店铺分类
        Route::get('shop_category/index',"ShopCategoryController@index");
        });

    //商户
    Route::domain('shop.ele.com')->namespace('Shop')->group(function () {
        Route::get('user/reg',"UserController@reg");
        Route::get('user/index',"UserController@index");
    });
    ```

13. 上传github

    * 第一次需要初始化
    * 以后每次需要先提交到本地
    * 再推送到github

### 数据表设计

#### 商家分类表shop_categories

| 字段名称 | 类型    | 备注               |
| -------- | ------- | ------------------ |
| id       | primary | 主键               |
| name     | string  | 分类名称           |
| img      | string  | 分类图片           |
| status   | int     | 状态：1显示，0隐藏 |

#### 商家信息表shops

| 字段名称         | 类型    | 备注                      |
| ---------------- | ------- | ------------------------- |
| id               | primary | 主键                      |
| shop_category_id | int     | 店铺分类ID                |
| shop_name        | string  | 名称                      |
| shop_img         | string  | 店铺图片                  |
| shop_rating      | float   | 评分                      |
| brand            | boolean | 是否是品牌                |
| on_time          | boolean | 是否准时送达              |
| fengniao         | boolean | 是否蜂鸟配送              |
| bao              | boolean | 是否保标记                |
| piao             | boolean | 是否票标记                |
| zhun             | boolean | 是否准标记                |
| start_send       | float   | 起送金额                  |
| send_cost        | float   | 配送费                    |
| notice           | string  | 店公告                    |
| discount         | string  | 优惠信息                  |
| status           | int     | 状态:1正常,0待审核,-1禁用 |

#### 商家账号表users

| 字段名称       | 类型    | 备注               |
| -------------- | ------- | ------------------ |
| id             | primary | 主键               |
| name           | string  | 名称               |
| email          | email   | 邮箱               |
| password       | string  | 密码               |
| remember_token | string  | token              |
| status         | int     | 状态：1启用，0禁用 |
| shop_id        | int     | 所属商家           |

### 要点难点及解决方案



## Day02

### 开发任务

- 完善day1的功能，使用事务保证商家信息和账号同时注册成功
- 平台：平台管理员账号管理
- 平台：管理员登录和注销功能，修改个人密码(参考微信修改密码功能)
- 平台：商户账号管理，重置商户密码
- 商户端：商户登录和注销功能，修改个人密码
- 修改个人密码需要用到验证密码功能,[参考文档](https://laravel-china.org/docs/laravel/5.5/hashing)
- 商户登录正常登录，登录之后判断店铺状态是否为1，不为1不能做任何操作

### 实现步骤

1. 在商户端口和平台端都要创建BaseController 以后都要继承自己的BaseController

2. 商户的登录和以前一样

3. 平台的登录，模型中必需继承 use Illuminate\Foundation\Auth\User as Authenticatable

4. 设置配置文件config/auth.php 

   ```php
    'guards' => [
           //添加一个guards
           'admin' => [
               'driver' => 'session',
               'provider' => 'admins',//数据提示者
           ],

          
       ],
    'providers' => [
        //提供商户登录
           'users' => [
               'driver' => 'eloquent',
               'model' => \App\Models\User::class,
           ],
        //提供平台登录
           'admins' => [
               'driver' => 'eloquent',
               'model' => \App\Models\Admin::class,
           ],
       ],
   ```

5. 平台登录的时候

   ```php
   Auth::guard('admin')->attempt(['name'=>$request->post('name'),'password'=>$request->password])
   ```

6. 平台AUTH权限判断

   ```php
   public function __construct()
       {
           $this->middleware('auth:admin')->except("login");
       }

   ```

7. 设置认证失败后回跳地址 在Exceptions/Handler.php后面添加

   ```php
   /**
        * 重写实现未认证用户跳转至相应登陆页
        * @param \Illuminate\Http\Request $request
        * @param AuthenticationException $exception
        * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
        */
       protected function unauthenticated($request, AuthenticationException $exception)
       {

           //return $request->expectsJson()
           //            ? response()->json(['message' => $exception->getMessage()], 401)
           //            : redirect()->guest(route('login'));
           if ($request->expectsJson()) {
               return response()->json(['message' => $exception->getMessage()], 401);
           } else {
               return in_array('admin', $exception->guards()) ? redirect()->guest('/admin/login') : redirect()->guest(route('user.login'));
           }
       }
   ```


## DAY03

### 开发任务

商户端 

- 菜品分类管理 
- 菜品管理 
要求 
- 一个商户只能有且仅有一个默认菜品分类 
- 只能删除空菜品分类 
- 必须登录才能管理商户后台（使用中间件实现） 
- 可以按菜品分类显示该分类下的菜品列表 
- 可以根据条件（按菜品名称和价格区间）搜索菜品

### 实现步骤

### 要点难点

## Day04

### 开发任务

优化 
\- 将网站图片上传到阿里云OSS对象存储服务，以减轻服务器压力(<https://github.com/jacobcyl/Aliyun-oss-storage>) 
\- 使用webuploder图片上传插件，提升用户上传图片体验

平台 
\- 平台活动管理（活动列表可按条件筛选 未开始/进行中/已结束 的活动） 
\- 活动内容使用ueditor内容编辑器(<https://github.com/overtrue/laravel-ueditor>)

商户端 
\- 查看平台活动（活动列表和活动详情） 
\- 活动列表不显示已结束的活动

### 实现步骤

#### 1.阿里云OSS

1. 进入阿里云官方网站，登录后充值1块，然后开通OSS对象存储
2. 新建OSS对象存储空间，设置公共读类型
3. 右键用户图像 创建 AccessKey 得到AccessKey ID，Access Key Secret备用

#### 2.代码实现

1. composer 安装 composer require jacobcyl/ali-oss-storage

2. 添加如下配置到app/filesystems.php

   ```php
   'disks'=>[
       ...
       'oss' => [
               'driver'        => 'oss',
               'access_id'     => '<Your Aliyun OSS AccessKeyId>',
               'access_key'    => '<Your Aliyun OSS AccessKeySecret>',
               'bucket'        => '<OSS bucket name 空间名称>',
               'endpoint'      => '<the endpoint of OSS, E.g: oss-cn-hangzhou.aliyuncs.com | custom domain, E.g:img.abc.com>', // OSS 外网节点或自定义外部域名
               'debug'         => false
       ],
       ...
   ]
   ```

3. 可以选择配置.env文件，提升体验

   ```php
   ALIYUN_OSS_URL=http://php0325ele.oss-cn-shenzhen.aliyuncs.com/
   ACCESS_ID=LTAICkzbQn0fTiHc
   ACCESS_KEY=xRoz5ISd0e8GMo2YnStxneXRbAF5P5
   BUCKET=php0325ele
   ENDPOINT=oss-cn-shenzhen.aliyuncs.com
   ```

   ​

4. 上传实现

   ```php
    $file=$request->file('img');
    if ($file!==null){
                  //上传文件
                  $fileName= $file->store("test","oss");

                  dd(env("ALIYUN_OSS_URL").$fileName);

               }
   ```

5. 缩略图实现

   http://image-demo.oss-cn-hangzhou.aliyuncs.com/example.jpg?x-oss-process=image/resize,w_300,h_300

#### 3.Webupload

1. 下载压缩包，并解压到public目录

2. 在基础模板中引入

   ```php
     <link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
     <script type="text/javascript" src="/webuploader/webuploader.js"></script>
   ```

3. 复制HTML代码到添加视图里

   ```html
   <div id="uploader-demo" class="wu-example">
       <div id="fileList" class="uploader-list">
       </div>
       <div id="filePicker">选择图片</div>
   </div>
   ```

4. 添加CSS样式到基础模板

   ```css

   ```



5. 添加JS代码到当前视图

   ```
    <script>
           // 图片上传demo
           jQuery(function () {
               var $ = jQuery,
                   $list = $('#fileList'),
                   // 优化retina, 在retina下这个值是2
                   ratio = window.devicePixelRatio || 1,

                   // 缩略图大小
                   thumbnailWidth = 100 * ratio,
                   thumbnailHeight = 100 * ratio,

                   // Web Uploader实例
                   uploader;

               // 初始化Web Uploader
               uploader = WebUploader.create({

                   // 自动上传。
                   auto: true,

                   // swf文件路径
                   swf: '/webuploader/Uploader.swf',

                   formData: {
                       // 这里的token是外部生成的长期有效的，如果把token写死，是可以上传的。
                       _token: '{{csrf_token()}}'
                       // 我想上传时再请求服务器返回token，改怎么做呢？反复尝试而不得。谢谢大家了！
                       //uptoken_url: '127.0.0.1:8080/examples/upload_token.php'
                   },

                   // 文件接收服务端。
                   server: '{{route('menu.upload')}}',
   ```


                   // 选择文件的按钮。可选。
                   // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                   pick: '#filePicker',
    
                   // 只允许选择文件，可选。
                   accept: {
                       title: 'Images',
                       extensions: 'gif,jpg,jpeg,bmp,png',
                       mimeTypes: 'image/*'
                   }
               });
    
               // 当有文件添加进来的时候
               uploader.on('fileQueued', function (file) {
                   var $li = $(
                       '<div id="' + file.id + '" class="file-item thumbnail">' +
                       '<img>' +
                       '<div class="info">' + file.name + '</div>' +
                       '</div>'
                       ),
                       $img = $li.find('img');
    
                   $list.append($li);
    
                   // 创建缩略图
                   uploader.makeThumb(file, function (error, src) {
                       if (error) {
                           $img.replaceWith('<span>不能预览</span>');
                           return;
                       }
    
                       $img.attr('src', src);
                   }, thumbnailWidth, thumbnailHeight);
               });
    
               // 文件上传过程中创建进度条实时显示。
               uploader.on('uploadProgress', function (file, percentage) {
                   var $li = $('#' + file.id),
                       $percent = $li.find('.progress span');
    
                   // 避免重复创建
                   if (!$percent.length) {
                       $percent = $('<p class="progress"><span></span></p>')
                           .appendTo($li)
                           .find('span');
                   }
    
                   $percent.css('width', percentage * 100 + '%');
               });
    
               // 文件上传成功，给item添加成功class, 用样式标记上传成功。
               uploader.on('uploadSuccess', function (file, data) {
                   //console.dir(data);
                   $('#' + file.id).addClass('upload-state-done');
    
                   //找到goods_img  设置goods_img的value值
                   $("#goods_img").val(data.url);
    
               });
    
               // 文件上传失败，现实上传出错。
               uploader.on('uploadError', function (file) {
                   var $li = $('#' + file.id),
                       $error = $li.find('div.error');
    
                   // 避免重复创建
                   if (!$error.length) {
                       $error = $('<div class="error"></div>').appendTo($li);
                   }
    
                   $error.text('上传失败');
               });
    
               // 完成上传完了，成功或者失败，先删除进度条。
               uploader.on('uploadComplete', function (file) {
                   $('#' + file.id).find('.progress').remove();
               });
           });
       </script>
   ```

> 1.设置正确的 swf 路径

> 2.设置formData 通过CSRF验证

> 3.设置 server: '{{route('menu.upload')}}', 用于上传文件

> 4.在上传成功的回调函数中需要添加第二个参数 data

> 5.上传成功后需要把上传成功的URL地址添加input中

6. 后端图片处理

   ```php
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
   ```



## Day05

### 开发任务

接口开发 

- 商家列表接口(支持商家搜索) 
- 获取指定商家接口

### 实现步骤

1. 在routes/api.php写API接口
2. 在public/api.js 配置相关路由
3. 参考之前接口文件获取数据
4. 书写API接口文档 https://www.showdoc.cc/web/#/
5. 安装POSTMAN调试


## Day06

### 开发任务

接口开发 

- 用户注册 
- 用户登录 
- 发送短信 
  要求 
- 创建会员表 
- 短信验证码发送成功后,保存到redis,并设置有效期5分钟 
- 用户注册时,从redis取出验证码进行验证

### 实现步骤

#### 1.短信发送

参考 https://packagist.org/packages/mrgoon/aliyun-sms 使用非Laravel框架方法

#### 2.redis使用

参考 https://laravel-china.org/docs/laravel/5.5/redis/1331

### 3.会员注册实现

1. 接收手机号
2. 生成随机验证码
3. 把验证码存在redis中