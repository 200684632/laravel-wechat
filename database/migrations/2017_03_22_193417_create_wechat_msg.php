<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatMsg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wechat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->default('')->comment('发送者id');
            $table->bigInteger('msg_id')->defalt('')->comment('消息id');
            $table->string('headimgurl')->default('')->comment('发送者头像');
            $table->string('nickname')->default('')->comment('发送者昵称');
            $table->string('type')->default('')->comment('消息类型');
            $table->string('content')->default('')->comment('消息内容');
            $table->integer('is_read')->default(0)->comment('是否已读,0为未读');
            $table->integer('reply_type')->default(0)->commit('回复类型');
            $table->string('reply_content')->default('')->commit('回复内容');
            $table->timestamp('send_at')->nullable()->comment('发送时间');
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
        Schema::dropIfExists('wechat_messages');
    }
}
