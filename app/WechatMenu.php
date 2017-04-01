<?php

namespace App;

use EasyWeChat\Foundation\Application;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

/**
 * App\WechatMenu
 *
 * @property int $id
 * @property int $parent_id 父级id
 * @property int $order 排序
 * @property string $title 菜单名，对应微信接口的 name
 * @property string $type 菜单类型 click or view
 * @property string $url 链接
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\WechatMenu whereUrl($value)
 * @mixin \Eloquent
 */
class WechatMenu extends Model
{
    use ModelTree, AdminBuilder;


    public $wechat;

    public function __construct(array $attributes = [])
    {
        $this->wechat = new Application(config('wechat'));
        parent::__construct($attributes);
    }

    public static function menu_type_list()
    {
        return ['view' => '页面（view）', 'click' => '点击（click）', '' => '无类型'];
    }

    public static function get_children($id)
    {
        return self::where('parent_id', $id)->get();

    }
}
