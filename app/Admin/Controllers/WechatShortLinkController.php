<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\WechartShortLink;
use Encore\Admin\Controllers\ModelForm;
use EasyWeChat\Foundation\Application;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatShortLinkController extends Controller
{
    use ModelForm;
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('列表');
            $content->description('短链接管理');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑');
            $content->description('短链接管理');
            $content->body($this->form()->edit($id));
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加');
            $content->description('短链接管理');

            $content->body($this->form());
        });
    }
    protected function grid()
    {
        return Admin::grid(WechartShortLink::class, function (Grid $grid) {
            $grid->paginate();
            $grid->id('id');
            $grid->link('原链接');
            $grid->short_link('短链接');
            $grid->admin_user()->name('发布者');
        });
    }

    protected function form()
    {
        return Admin::form(WechartShortLink::class, function (Form $form) {

            $form->hidden('add_user_id')->default(Admin::user()->id);
            $form->url('link', '原链接');
            $form->hidden('short_link');
            $form->saving(function (Form $form) {
                //...
                $wechat = new Application(config('wechat'));
                $data = $wechat->url->shorten($form->input('link'));
                $data = json_decode($data,true);
                $form->short_link = $data['short_url'];
            });
        });
    }
}
