<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WechatBroadcastLog
 *
 * @property int $id
 * @property string $media_id 素材id
 * @property int $broadcast_user_id 群发操作人
 * @property string $broadcast_at 群发时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereBroadcastAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereBroadcastUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereMediaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatBroadcastLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\AdminUser $admin_user
 */
class WechatBroadcastLog extends Model
{
    public function admin_user()
    {
        return $this->belongsTo('App\AdminUser', 'broadcast_user_id');
    }
}
