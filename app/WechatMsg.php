<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class WechatMsg extends Model
{
    //
    protected $table = 'wechat_msg';
    public function scopeInsetDat($msg_id,$type,$content,$openid,$headimgurl,$nickname,$createTime){
        WechatMsg::insert(['msg_id'=>$msg_id,'type'=>$type,'content'=>$content,'openid'=>$openid,'headimgurl'=>$headimgurl,'nickname'=>$nickname,'create_at'=>$createTime]);
    }
}
