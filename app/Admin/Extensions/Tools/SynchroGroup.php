<?php

namespace App\Admin\Extensions\Tools;

use App\WechatMessage;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class SynchroGroup extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        Admin::script($this->script());

        return <<<EOT
<div class="btn-group" data-toggle="buttons">
    <label class="btn btn-twitter btn-sm grid-group">
        <input type="checkbox"><i class="fa fa-rotate-right"></i> 同步
    </input>
</div>
EOT;
    }
    public function script()
    {
      $url = env('APP_URL');
        return <<<EOT
$('.grid-group').click(function () {
    $.ajax({
        method: 'get',
        url: '$url/admin/wechat_groups/synchro',
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});
EOT;
    }
}