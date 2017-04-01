<?php

namespace App;

use EasyWeChat\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WechatTag extends Model
{
    private $wechat;

    public function __construct(array $attributes = [])
    {
        $this->wechat = new Application(config('wechat'));
        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->belongsToMany('App\WechatUser', 'wechat_user_tags', 'tag_id', 'open_id');
    }



    public function scopeTags()
    {
        $res = $this->wechat->user_tag->lists();
        $res = json_decode($res, true);
        return count($res['tags']);
    }

    public static function deleteTag($tag_id)
    {
        DB::table('wechat_tags')->where('id', $tag_id)->delete();
    }


}
