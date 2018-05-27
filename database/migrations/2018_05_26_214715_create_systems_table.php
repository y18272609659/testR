<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gameTime'); # 游戏日期，在 init 程序中设定初始日期，每次更新时，与上一条数据、本次 created_at 相互转换为正确的时间戳
            $table->string('pack', 12); # 游戏包，标志大版本 eg.'创世'、'燃烈'
            $table->float('version', 5, 2); # 游戏版本 eg. 3.46
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
        Schema::dropIfExists('systems');
    }
}
