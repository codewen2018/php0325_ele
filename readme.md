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

