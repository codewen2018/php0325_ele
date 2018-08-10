<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Shop extends Model
{
    use Searchable;
    //public $asYouType = true;
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {

        $array = $this->only('id','shop_name');

        // Customize array...

        return $array;
    }




    public static $statusArray=['1'=>'正常','0'=>'待审核',"-1"=>'已禁用'];


    protected $fillable = ['shop_name', 'shop_img', 'shop_rating', 'brand', 'on_time',
        'fengniao', 'bao', 'piao', 'zhun', 'start_send', 'send_cost', 'notice', 'discount', 'shop_cate_id'];

    //得到商家分类
    public function cate(){

        return $this->belongsTo(ShopCategory::class,"shop_cate_id");
    }
    //通过商家找用户
    public function user(){
        return $this->hasOne(User::class);
    }

}
