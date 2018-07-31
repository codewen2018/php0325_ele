<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_id')->comment("订单Id");
            $table->integer('goods_id')->comment("商品Id");
            $table->integer('amount')->comment("购买数量");
            $table->string('goods_name')->comment("商品名称");
            $table->string('goods_img')->comment("商品图片");
            $table->decimal('goods_price')->comment("商品价格");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_goods');
    }
}
