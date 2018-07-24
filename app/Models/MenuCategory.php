<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    //设置隐藏字段
    public $fillable = ['name', 'type_accumulation', 'shop_id', 'description', 'is_selected'];
}
