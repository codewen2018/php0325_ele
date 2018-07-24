<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique()->comment('分类名称');
            $table->string('type_accumulation')->nullable()->comment('菜品编号');
            $table->integer('shop_id')->comment('所属商铺');
            $table->string('description')->comment('描述');
            $table->string('is_selected')->default(0)->comment('是否默认分类：1是，0否');

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
        Schema::dropIfExists('menu_categories');
    }
}
