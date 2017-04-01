<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\BroadcastToWechat;
use App\WechatBroadcastLog;
use App\WechatMaterial;

use EasyWeChat\Foundation\Application;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class WechatMaterialController extends Controller
{
    use ModelForm;

    /**
     * 复写 ModelForm 的删除方法
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wechat = new Application(config('wechat'));

        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $wechat->material->delete($id);
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

            $content->header('素材列表');
            $content->description('微信管理');

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

            $content->header('编辑素材');
            $content->description('微信管理');

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

            $content->header('添加素材');
            $content->description('微信管理');

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
        return Admin::grid(WechatMaterial::class, function (Grid $grid) {
            $grid->disableCreation();
            $grid->disableFilter();
            $grid->media_id();
            $grid->update_time('上传时间')->value(function ($update_time) {
                return date('Y-m-d H:i:s', $update_time);
            });
            $grid->column('content', '图文详情')->expand(function () {
                if ($this->content) {
                    $headers = ['标题', '作者', '原文链接', '预览'];
                    $rows = [];
                    foreach ($this->content['news_item'] as $val) {
                        $rows[] = [$val['title'], $val['author'], "<a target='_blank' href='{$val['content_source_url']}'></a>", "<a target='_blank' href='{$val['url']}'>预览</a>"];
                    }
                    $table = new Table($headers, $rows);
                    $box = new Box('图文详情', $table);
                    return $box;
                } else {
                }
            }, '图文详情');

            // 工具
            $grid->tools(function ($tools) {
                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->add('群发（慎重）', new BroadcastToWechat()); // 批量取消发布
                });
            });
            $grid->disableActions();
            $grid->actions(function ($actions) {
                $actions->disableEdit();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(WechatMaterial::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function broadcast_to_wechat(Request $request)
    {
        $media_id_list = $request->get('ids');
        if (count($media_id_list) > 1) {
            return ['status' => 0, 'message' => '群发只能选中一条'];
        }
        $wechat = new Application(config('wechat'));
        $res = $wechat->broadcast->sendNews($media_id_list[0]);
        $res = json_decode($res, true);
        if ($res['errcode'] == 0) {
            // 插入群发日志
            $log = new WechatBroadcastLog();
            $log->media_id = $media_id_list[0];
            $log->broadcast_at = date('Y-m-d H:i:s');
            $log->broadcast_user_id = Admin::user()->id;
            $log->save();
        }
        return $res;

    }
}
