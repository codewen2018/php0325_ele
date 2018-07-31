<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoneyJifenToMemebers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memebers', function (Blueprint $table) {
            $table->decimal('money')->default(0)->comment('金钱');
            $table->integer('jifen')->default(0)->comment('积分');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memebers', function (Blueprint $table) {
            $table->dropColumn(['money', 'jifen']);
        });
    }
}
