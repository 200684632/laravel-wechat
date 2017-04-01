<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatBroadcastLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_broadcast_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_id')->default('')->comment('素材id');
            $table->integer('broadcast_user_id')->default(0)->comment('群发操作人');
            $table->timestamp('broadcast_at')->nullable()->comment('群发时间');
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
    }
}
