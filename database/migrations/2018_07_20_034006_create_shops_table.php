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
            $table->string('shop_name' ,100)->unique();
            $table->string('shop_img');
            $table->decimal('shop_rating')->nullable();
            $table->boolean('brand')->nullable();
            $table->boolean('on_time')->nullable();
            $table->boolean('fengniao')->nullable();
            $table->boolean('bao')->nullable();
            $table->boolean('piao')->nullable();
            $table->boolean('zhun')->nullable();
            $table->decimal('start_send');
            $table->decimal('send_cost');
            $table->string('notice');
            $table->string('discount');
            $table->timestamps();
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
