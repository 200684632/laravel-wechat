<?php

namespace App\Admin\Controllers;

use App\WechatTag;
use App\WechatGroup;
use App\Admin\Extensions\Tools\SynchroTag;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Illuminate\Support\Facades\DB;

class WechatUserTagController extends Controller
{
    use ModelForm;

    public function destroy($id)
    {
        $wechat = new Application(config('wechat'));
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $wechat->user_tag->delete($id);
            WechatTag::deleteTag($id);
            WechatGroup::deleteGroup($id);
        }

        return response()->json([
            'status' => true,
            'message' => trans('admin::lang.delete_succeeded'),
        ]);
    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('列表');
            $content->description('微信用户标签管理');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑');
            $content->description('微信用户标签管理');
            $content->body($this->form()->edit($id));
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('添加标签');
            $content->description('微信用户标签管理');

            $content->body($this->form());
        });
    }
    protected function grid()
    {
        return Admin::grid(WechatTag::class, function (Grid $grid) {
            $grid->paginate();
            $grid->id('序号');
            $grid->name('名称');
            $grid->count('数目');

//            $grid->actions(function ($actions) {
//                $actions->disableEdit();
//            });
            $grid->tools(function ($tools) {
                $tools->append(new SynchroTag());
            });
        });
    }

    protected function form()
    {
        return Admin::form(WechatTag::class, function (Form $form) {

            $form->hidden('id');
//            $form->display('count', '数目');

            $form->text('name', '名称');
            $form->saving(function (Form $form) {
                $wechat = new Application(config('wechat'));
                if(isset($form->id)){
                    $id = $wechat->user_tag->create($form->input('name'));
                    $id = json_decode($id,true);
                    WechatTag::insert(['id'=>$id['tag']['id'],'name'=>$id['tag']['name'],'count'=>0]);
                }else{
                    $wechat->user_tag->update($form->id, $form->name);
                    DB::table('wechat_tags')->where('id',$form->id)->update(['name'=>$form->name]);
                }
            });
        });
    }
    public function synchro(){
        $wechat = new Application(config('wechat'));
        $data = $wechat->user_tag->lists();
        $data = json_decode($data,true);
        foreach ($data['tags'] as $val){
            if (!(DB::table('wechat_tags')->where('id',$val['id'])->count())) {
                WechatTag::insert(['id'=>$val['id'],'name'=>$val['name'],'count'=>$val['count']]);
            }else{
                DB::table('wechat_tags')->where('id',$val['id'])->update(['name'=>$val['name'],'count'=>$val['count']]);
            }
        }
    }
}
