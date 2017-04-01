<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class WechartShortLink extends Model
{
    //
    protected $table = 'wechat_short_links';
    public function admin_user(){
        return $this->belongsTo('App\AdminUser','add_user_id');
    }
}
