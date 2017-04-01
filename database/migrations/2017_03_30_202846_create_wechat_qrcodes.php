<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatQrcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wechat_qrcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('qrcodeurl')->default('')->comment('二维码地址');
            $table->string('describe')->default('')->comment('描述');
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
        Schema::dropIfExists('wechat_qrcodes');
    }
}
