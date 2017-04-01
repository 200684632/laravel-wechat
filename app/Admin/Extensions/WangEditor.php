<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin::form.editor';

    protected static $css = [
        '/packages/wangEditor-2.1.22/dist/css/wangEditor.min.css',
    ];

    protected static $js = [
        '/packages/wangEditor-2.1.22/dist/js/wangEditor.min.js',
    ];

    public function render()
    {
        $token = csrf_token();
        $mapAk = env('BAIDU_MAP_AK');
        $this->script = <<<EOT

var editor = new wangEditor('{$this->id}');
    editor.config.mapAk = '{$mapAk}';
    editor.config.uploadImgFileName = 'image';
    editor.config.uploadImgUrl = '/admin/api/image';
    editor.config.uploadParams = {
    _token: '$token'
};
    editor.create();

EOT;
        return parent::render();

    }
}