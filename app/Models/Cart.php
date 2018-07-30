<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    public $fillable=['user_id',"goods_id",'amount'];
}
