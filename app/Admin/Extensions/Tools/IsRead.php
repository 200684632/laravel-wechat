<?php

namespace App\Admin\Extensions\Tools;

use App\WechatMessage;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class IsRead extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        Admin::script($this->script());

        $isRead = (Request::get('isRead') == 1) ? 'active' : '';
        $checked = (Request::get('isRead') == 1) ? 'checked' : '';
        $num = WechatMessage::where('is_read',0)->count();
        $num = (Request::get('isRead') == 1) ? '' : $num;
        $text = (Request::get('isRead') == 1) ? '已回复' : '未回复';
        $tag = '';
        if ($num){
            $tag = '<span class="label label-danger">'.$num.'</span>';
        }
        return <<<EOT
<div class="btn-group" data-toggle="buttons">
    <label class="btn btn-twitter btn-sm grid-isread $isRead">
        <input type="checkbox" $checked><i class="fa fa-comment"></i> $text&nbsp;&nbsp;&nbsp;&nbsp;$tag
    </label>
</div>
EOT;
    }
    public function script()
    {
        $url = Request::fullUrlWithQuery(['isRead' => '_isRead_']);

        return <<<EOT
$('.grid-isread').click(function () {
    var status = $(this).find('input')[0].checked ? 0 : 1;
    $.pjax({container:'#pjax-container', url: "$url".replace('_isRead_', status) });
});
EOT;
    }
}