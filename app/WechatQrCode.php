<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class WechatQrCode extends Model
{
    //
    protected $table = 'wechat_qrcodes';
    public function admin_user(){
        return $this->belongsTo('App\AdminUser','add_user_id');
    }
}
