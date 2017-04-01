<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class PostToWechat extends BatchAction
{

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '/{$this->resource}/post_to_wechat',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
        },
        success: function (data) {
            if (data.status != 0) {
                $.pjax.reload('#pjax-container');
                toastr.error(data.message);
            } else {
                $.pjax.reload('#pjax-container');
                toastr.success(data.message);
            } 
        }
    });
});

EOT;

    }
}