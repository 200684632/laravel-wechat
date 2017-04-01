<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class UpdateMenuToWechat extends BatchAction
{

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '/{$this->resource}/update_menu_to_wechat',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
        },
        dataType: 'json',
        success: function (data) {     
            if (data.errcode != 0) {
                $.pjax.reload('#pjax-container');
                toastr.error(data.errmsg);
            } else {
                $.pjax.reload('#pjax-container');
                toastr.success(data.errmsg);
            } 
        }
    });
});

EOT;

    }
}