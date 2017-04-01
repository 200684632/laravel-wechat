<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WechatMessage extends Model
{
    //
    protected $table = 'wechat_messages';
    public static function insetData($msg_id,$type,$content,$openid,$headimgurl,$nickname,$createTime){
        WechatMessage::insert(['msg_id'=>$msg_id,'type'=>$type,'content'=>$content,'openid'=>$openid,'headimgurl'=>$headimgurl,'nickname'=>$nickname,'send_at'=>$createTime]);
    }
    public static function updateIsRead($id){
        DB::table('wechat_messages')->where('id',$id)->update(['is_read'=>1]);
    }
}
