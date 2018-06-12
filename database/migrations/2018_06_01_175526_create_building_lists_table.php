<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');

            $table->unsignedInteger('start'); # 开始时间
            $table->unsignedInteger('end'); # 截至时间
            $table->string('category', 18); # 建筑类型
            $table->unsignedTinyInteger('level'); # 建筑级别
            $table->unsignedTinyInteger('action')->default(1); # 操作类型，0 为空闲，1 为建筑，2 为拆除
            $table->unsignedSmallInteger('number'); # 改动数量

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
        Schema::dropIfExists('building_lists');
    }
}
