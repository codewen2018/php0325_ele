<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            //店铺类别
            $table->unsignedInteger('shop_cate_id');
            $table->string('shop_name' ,100)->unique()->comment('店铺名称');
            $table->string('shop_img')->comment('店铺图片');
            $table->decimal('shop_rating')->nullable()->comment('评分');
            $table->boolean('brand')->nullable()->comment('是否品牌');
            $table->boolean('on_time')->nullable()->comment('是否准时送达');
            $table->boolean('fengniao')->nullable()->comment('是否蜂鸟配送');
            $table->boolean('bao')->nullable()->comment('是否保标记');
            $table->boolean('piao')->nullable()->comment('是否票标记');
            $table->boolean('zhun')->nullable()->comment('是否准标记');
            $table->decimal('start_send')->comment('起送金额');
            $table->decimal('send_cost')->comment('配送费');
            $table->string('notice')->comment('店公告');
            $table->string('discount')->comment('优惠信息');
           $table->integer('status')->comment('状态:1正常,0待审核,-1禁用');
            $table->timestamps();
            //设置外键
            $table->foreign('shop_cate_id')->references('id')->on('shop_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
