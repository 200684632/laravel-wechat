<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\UpdateMenuToWechat;
use App\Http\Controllers\Controller;
use App\WechatMenu;

use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;


class WechatMenuController extends Controller
{
    use ModelForm;


    /**
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('header');
            $content->description('description');
            $content->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->append($this->tree()->render());

                    // 当前微信菜单
                    $model = new WechatMenu();
                    $current_menu_info = $model->wechat->menu->current();
                    $current_menu_info = json_decode($current_menu_info, true);
                    $button_list = $current_menu_info['selfmenu_info']['button'];
                    $headers = ['ID', '名称', '类型', '有无子菜单'];
                    $rows = [];
                    foreach ($button_list as $key => $button) {
                        $rows[] = [$key + 1, $button['name'], isset($button['type']) ? $button['type'] : '', isset($button['sub_button']) ? '有' : '无'];
                    }
                    $table = new Table($headers, $rows);
                    $column->append((new Box('当前微信菜单', $table))->style('success'));

                });

                $row->column(6, function (Column $column) {
                    $column->append($this->grid()->render());

                    /*// 更新微信菜单
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action('save_to_wechat');
                    $form->multipleSelect('ids', '一级菜单')->options(WechatMenu::where('parent_id', 0)->pluck('title', 'id'));
                    $form->disablePjax();
                    $column->append((new Box('更新微信菜单', $form))->style('warning'));*/
                });
            });
        });
    }

    /**
     * @param $id
     * @return \Encore\Admin\Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * @return \Encore\Admin\Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    protected function tree()
    {
        return WechatMenu::tree(function (Tree $tree) {

            $tree->branch(function ($branch) {


                return "{$branch['id']} - {$branch['title']}";

            });

        });
    }


    protected function grid()
    {
        return Admin::grid(WechatMenu::class, function (Grid $grid) {
            $grid->model()->where('parent_id', 0)->orderBy('order');
            $grid->disableActions();
            $grid->disablePagination();
            $grid->disableCreation();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->column('title', '名称')->editable();
            $grid->column('type', '类型');
            $grid->column('sub_button', '有无子菜单')->value(function() {
                $children = WechatMenu::get_children($this->id);
                if (count($children)) {
                    return '有';
                } else {
                    return '无';
                }

            });

            // 工具
            $grid->tools(function ($tools) {
                $tools->batch(function (Grid\Tools\BatchActions $batch) {
                    $batch->add('更新微信菜单', new UpdateMenuToWechat()); // 更新微信菜单
                });
            });
            // 动作
            $grid->actions(function ($actions) {
                // 禁用删除按钮
                $actions->disableDelete();

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
        return Admin::form(WechatMenu::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->select('parent_id', '父级菜单')->options(WechatMenu::selectOptions());
            $form->text('title', '菜单名称')->rules('required');
            $form->text('url', '链接');
            $form->select('type', '类型')->options(WechatMenu::menu_type_list())->default('页面');
            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function update_menu_to_wechat(Request $request)
    {
        $buttons = [];
        if ($request->get('ids')) {
            foreach (WechatMenu::find($request->get('ids')) as $post) {
                $children = WechatMenu::get_children($post->id);
                if (count($children)) {
                    $sub_button = [];
                    foreach ($children as $sub) {
                        $sub_button[] = ['type' => $sub->type, 'name' => $sub->title, 'url' => $sub->url];
                    }
                    $buttons[] = ['name' => $post->title, 'sub_button' => $sub_button];
                } else {
                    $buttons[] = ['type' => $post->type, 'name' => $post->title, 'url' => $post->url];
                }
            }
        }
        $model = new WechatMenu();
        if ($buttons) {
            $model->wechat->menu->destroy();
            return $model->wechat->menu->add($buttons);
        } else {
            return $model->wechat->menu->destroy();
        }
    }
}
