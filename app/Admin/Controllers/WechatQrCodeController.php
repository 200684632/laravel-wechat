<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\WechatQrCode;
use Encore\Admin\Controllers\ModelForm;
use EasyWeChat\Foundation\Application;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class WechatQrCodeController extends Controller
{
    use ModelForm;
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('列表');
            $content->description('二维码管理');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑');
            $content->description('二维码管理');
            $content->body($this->form()->edit($id));
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加标签');
            $content->description('二维码管理');

            $content->body($this->form());
        });
    }
    protected function grid()
    {
        return Admin::grid(WechatQrCode::class, function (Grid $grid) {
            $grid->id('id');
            $grid->qrcodeurl('二维码')->image('',100,100);
            $grid->describe('描述');
            $grid->admin_user()->name('发布者');
            $grid->actions(function ($actions) {
                $actions->disableEdit();
            });
        });
    }

    protected function form()
    {
        return Admin::form(WechatQrCode::class, function (Form $form) {
            $form->text('describe','描述')->placeholder('请进行简单的描述');
            $form->hidden('add_user_id')->default(Admin::user()->id);
            $form->hidden('qrcodeurl');
            $form->saving(function (Form $form) {
                //...
                $result = DB::table('wechat_qrcodes')->orderBy('id')->first();
                $parameter = $result->id;
                $wechat = new Application(config('wechat'));
                $data = $wechat->qrcode->forever($parameter+1);
                $ticket = $data->ticket;
                $url = $wechat->qrcode->url($ticket);
                $form->qrcodeurl = $url;
            });
        });
    }
}
