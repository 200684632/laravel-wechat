<?php

namespace App;

use EasyWeChat\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\WechatUser
 *
 * @mixin \Eloquent
 */
class WechatUser extends Model
{
     // 设置主键及其数值类型
     protected $keyType = 'string';

    protected $wechat;

    public function __construct(array $attributes = [])
    {
        $this->wechat = new Application(config('wechat'));
        parent::__construct($attributes);
    }
    public function tags()
    {
        return $this->belongsToMany('App\WechatTag','wechat_user_tags','open_id', 'tag_id');
    }

    public function scopeUsers(){
        $list = $this->wechat->user->lists();
        $list = json_decode($list, true);
        return count($list['data']['openid']);
    }

    public static function insertData($headimgurl,$nickname,$remark,$sex,$language,$country,$province,$city,$subscribe_time,$openid){
        if (DB::table('wechat_users')->where('id',$openid)->count()){
            DB::table('wechat_users')->where('id', $openid)->update(['subscribe_at' => date('Y-m-d H:i:s'), 'is_subscribe' => 1]);
        }else{
            WechatUser::insert(['headimgurl' => $headimgurl, 'nickname' => $nickname, 'remark' => $remark, 'sex' => $sex, 'language' => $language, 'country' => $country, 'province' => $province, 'city' => $city, 'subscribe_at' => $subscribe_time, 'id' => $openid]);
        }
    }
    public static function unsubscribe($openid){
        DB::table('wechat_users')->where('id',$openid)->update(['unsubscribe_at'=>date('Y-m-d H:i:s'),'is_subscribe'=>2]);
    }

}
