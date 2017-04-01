<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\AbstractTool;

class GetWeiboToken extends AbstractTool
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $config = config('weibo');
        $auth = new \SaeTOAuthV2($config['WB_AKEY'], $config['WB_SKEY']);
        $url = $auth->getAuthorizeURL($config['WB_CALLBACK_URL']);
        return <<<EOT
     <a class="btn btn-info btn-sm" href="{$url}" target="_black">
       <i class="fa fa-weibo"></i>获取微博授权</a>
EOT;
    }

}