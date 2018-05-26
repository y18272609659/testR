<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->unsignedInteger('people')->default(200); # 人口 - 成人
            $table->float('child', 5, 2)->default(0); # 人口 - 孩子
            $table->unsignedInteger('food')->default(3000); # 食物(Max: 40 亿)
            $table->mediumInteger('foodOutput')->default(0); # 食物实际产出
            $table->unsignedInteger('wood')->default(2000); # 木材
            $table->mediumInteger('woodOutput')->default(0); # 木材实际产出
            $table->unsignedInteger('stone')->default(1000); # 石块
            $table->mediumInteger('stoneOutput')->default(0); # 石块实际产出
            $table->unsignedInteger('money')->default(3500); # 钱财
            $table->mediumInteger('moneyOutput')->default(0); # 钱财实际产出
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
        Schema::dropIfExists('resources');
    }
}