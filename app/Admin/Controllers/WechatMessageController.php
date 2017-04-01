<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\IsRead;
use App\WechatMessage;
use EasyWeChat\Message\News;
use EasyWeChat\Foundation\Application;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use EasyWeChat\Message\Text;

class WechatMessageController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('所有消息');
            $content->description('微信消息管理');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('编辑消息');
            $content->description('微信用户管理');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加用户');
            $content->description('微信用户管理');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(WechatMessage::class, function (Grid $grid) {
            $grid->disableBatchDeletion();
            if (request('isRead') == 1) {
                $grid->model()->where('is_read',0);
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
//                    $actions->disableEdit();
//                    $actions->prepend('<a href="'.env('APP_URL').'/admin/usermessage/'.$actions->getkey().'" title="查看"><i class="fa fa-eye"></i>&nbsp;&nbsp;</a>');
//                    $actions->append('<a href="" title="回复"><i class="fa fa-comment"></i></a>');
                });
            } else {
                $grid->model()->where('is_read',1);
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                });
            }
            $grid->disableCreation();
            $grid->model()->orderBy('send_at','desc');
            $grid->id('ID')->sortable();
            $grid->headimgurl('发送者头像')->image('', '', 23);
            $grid->nickname('发送者昵称');
            $grid->type('类型');
            $grid->is_read('状态')->value(function ($text){
                if ($text){
                    return '已回复';
                }else{
                    return '<p style="color: red">未回复</p>';
                }
            });
            $grid->content('内容')->display(function ($text){
                return str_limit($text, 20, '...');
            });
            $grid->send_at('发送时间');
            $grid->tools(function ($tools) {
                $tools->append(new IsRead());
            });


        });
    }
    protected function form()
    {
        return Admin::form(WechatMessage::class, function (Form $form) {
            $form->text('id','ID');
            $form->display('nickname','发送者昵称');
            $form->image('headimgurl', '头像')->resize(env('IMAGE_WIDTH'), null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->fit(env('IMAGE_WIDTH'), env('IMAGE_HEIGHT'), function ($constraint) {
                $constraint->upsize();
            })->uniqueName();
            $form->hidden('openid','OPEN ID');
            $form->display('type','类型');
            $form->display('content','内容');
            $form->display('send_at','发送时间');
            $form->select('reply_type','回复类型')->options(
                ['1'=>'文本']
            );
            $form->editor('reply_content', '回复内容')->attribute(['rows' => 15]);
            $form->saving(function (Form $form){
                WechatMessage::updateIsRead($form->input('id'));
            });
            $form->saved(function (Form $form) {
                if($form->input('reply_content') and $form->input('reply_type')){
                    if ($form->input('reply_type') == 1){
                        $wechat = new Application(config('wechat'));
                        $message = new Text(['content' => strip_tags($form->input('reply_content'))]);
                        $wechat->staff->message($message)->to($form->input('openid'))->send();
                    }elseif ($form->input('reply_type') == 2){
                        $wechat = new Application(config('wechat'));
                        $message = new News(['media_id' => $form->input('reply_content')]);
                        $wechat->staff->message($message)->to($form->input('openid'))->send();
                    }
                }
            });
        });
    }
}
