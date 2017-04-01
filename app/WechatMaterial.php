<?php

namespace App;

use EasyWeChat\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;


/**
 * App\WechatMaterial
 *
 * @mixin \Eloquent
 */
class WechatMaterial extends Model
{
    protected $primaryKey = 'media_id';
    protected $keyType = 'string';

    private $wechat;

    public function __construct(array $attributes = [])
    {
        $this->wechat = new Application(config('wechat'));
        parent::__construct($attributes);
    }

    public function paginate()
    {
        $type = 'news';
        $offset = 0;
        $count = 20; // max = 20
        $list = $this->wechat->material->lists($type, $offset, $count);
        $list = json_decode($list, true);
        $item_list = $list['item'];
        $item_list = static::hydrate($item_list);
        $paginator = new LengthAwarePaginator($item_list, $list['total_count'], 10);
        $paginator->setPath(url()->current());

        return $paginator;

    }

    public static function with($relations)
    {
        return new static;
    }

    public function findOrFail($media_id)
    {
        $media_info = $this->wechat->material->get($media_id);
        $media_info = json_decode($media_info, true);
        return static::newFromBuilder($media_info['news_item']);
    }


    public function save(array $options = [])
    {

        $data = $this->getAttributes();
        dd($data);
    }

}
