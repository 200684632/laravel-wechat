<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_users', function (Blueprint $table) {
            $table->string('id')->comment('用户openid');
            $table->string('headimgurl')->default('')->comment('用户头像');
            $table->string('nickname')->default('')->comment('用户昵称');
            $table->string('remark')->default('')->comment('备注');
            $table->integer('sex')->default(0)->comment('性别');
            $table->string('language')->default('')->comment('语言');
            $table->string('country')->default('')->comment('国家');
            $table->string('province')->default('')->comment('省');
            $table->string('city')->default('')->comment('市');
            $table->timestamp('subscribe_at')->nullable()->comment('关注时间');
            $table->timestamp('unsubscribe_at')->nullable()->comment('取关时间');
            $table->integer('open_platform_id')->comment('平台id');
            $table->tinyInteger('is_subscribe')->default(1)->commit('是否关注？1为关注，2为取关');
            $table->primary('id');
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
        Schema::dropIfExists('wechat_users');
    }
}
