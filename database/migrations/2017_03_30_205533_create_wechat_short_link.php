<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatShortLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wechat_short_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link')->default('')->comment('原链接');
            $table->string('short_link')->comment('短链接');
            $table->integer('add_user_id')->comment('添加人');
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
        //
        Schema::dropIfExists('wechat_short_links');
    }
}
