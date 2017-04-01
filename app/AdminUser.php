<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AdminUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $avatar 头像
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdminUser whereUsername($value)
 * @mixin \Eloquent
 */
class AdminUser extends Model
{
    //
    function articles(){
        return $this->hasMany('App\article','add_user_id');
    }
}
