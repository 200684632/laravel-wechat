<?php

namespace App\Admin\Extensions\Column;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * URL 定制样式
 * Class UrlWrapper
 * @package App\Admin\Extensions\Column
 */
class BaiduMap extends AbstractDisplayer

{
    protected function script()
    {
        return <<<EOT

$('.grid-baidumap').popover({
    title: "百度地图",
    html: true,
    trigger: 'focus'
});

EOT;

    }

    public function display()
    {
        Admin::script($this->script());

        $src = "http://api.map.baidu.com/staticimage/v2?ak=" . env('BAIDU_MAP_AK') . "&mcode=666666&center={$this->value}&width=300&height=200&zoom=11&markers={$this->value}&copyright=1";

        $baidu_map = "<img style='width:225px; height:150px;' src='{$src}' />";

        return <<<EOT

<div class="input-group" style="width:200px;">
  <input type="text" class="form-control input-sm" value="{$this->value}" />
  <span class="input-group-btn">
    <a class="btn btn-default btn-sm grid-baidumap" data-content="$baidu_map" data-toggle='popover' tabindex='0'>
        <i class="fa fa-map"></i>
    </a>
  </span>
</div>

EOT;

    }
}