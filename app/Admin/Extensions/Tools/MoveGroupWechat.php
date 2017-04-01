<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class MoveGroupWechat extends BatchAction
{
    protected $groupid;

    public function __construct($groupid)
    {
        $this->groupid = $groupid;
    }

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '/{$this->resource}/movegroup',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
            groupid: {$this->groupid}
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