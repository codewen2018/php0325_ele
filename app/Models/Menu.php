<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //设置可修改的字段
    public $fillable=['goods_name',"goods_price","goods_img","shop_id","cate_id","description",'tips','rating','month_sales','rating_count','satisfy_count','satisfy_rate','status'];




}
