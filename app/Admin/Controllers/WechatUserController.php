<?php

namespace App\Admin\Controllers;


use App\Admin\Extensions\Tools\SynchroUser;
use App\WechatUser;
use App\WechatTag;
use EasyWeChat\Foundation\Application;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WechatUserController extends Controller
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

            $content->header('用户列表');
            $content->description('微信用户管理');

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

            $content->header('编辑用户');
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
        return Admin::grid(WechatUser::class, function (Grid $grid) {
            $grid->disableCreation();
            //去掉删除按钮
            $grid->disableBatchDeletion();
            //$grid->paginate(10);
            //$grid->id('Open Id');
            $grid->model()->where('is_subscribe',1);
            $grid->headimgurl('头像')->image('', '', 23);
            $grid->nickname('昵称');
            $grid->remark('备注');
            $grid->sex('性别')->value(function ($sex) {
                if ($sex == 1) {
                    return '男';
                } else {
                    return '女';
                }
            });
            $grid->language('语言');
            $grid->country('国家');
            $grid->province('省');
            $grid->city('市');
            $grid->subscribe_at('关注时间');
            $grid->tags('标签')->pluck('name')->label();
            $grid->tools(function ($tools){
                $tools->append(new SynchroUser()); //与微信端同步
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->disableIdFilter();
                // 设置created_at字段的范围查询
                $filter->is('id','Open Id');
                $filter->like('nickname','昵称');
                $filter->is('sex', '性别')->select(['1'=>'男','2'=>'女']);
                $filter->is('province','省');
                $filter->is('city','市');
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereHas('tags', function ($query) use ($input) {
                        $query->where('name', $input);
                    });
                }, '标签');
                $filter->between('subscribe_at', '关注时间')->datetime();
            });

            $grid->actions(function ($actions) {
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
        return Admin::form(WechatUser::class, function (Form $form) {

            $form->hidden('id', 'Open ID');
            $form->display('nickname', '昵称');
            $form->display('sex', '性别')->help('1代表男，2代表女');
            $form->display('language', '语言');
            $form->display('area', '地区');
            $form->image('headimgurl')->attribute('readonly', 'readonly');
            $form->display('subscribe_at','关注时间');
            $form->text('remark', '备注');
            $form->multipleSelect('tags', '标签')->options(WechatTag::all()->pluck('name', 'id'));
            $form->saving(function (Form $form) {
                $wechat = new Application(config('wechat'));
                $wechat->user->remark($form->input('id'), $form->input('remark'));
                $tags = $form->input('tags');
                $data[] = $form->input('id');
                $num = count($tags)-1;
                for ($i=0;$i<$num;$i++){
                    $wechat->user_tag->batchTagUsers($data,$tags[$i]);
                }
            });
        });
    }


    public function tagUser(Request $request)
    {
        $ids = $request->get('ids');
        $tagid = $request->get('tagid');


        $wechat = new Application(config('wechat'));
        $tag = $wechat->user_tag->batchTagUsers($ids, $tagid);

        return $tag;
    }

    public function synchro($openid = '')
    {
        $wechat = new Application(config('wechat'));
        $data = $wechat->user->lists($openid);
        $data = json_decode($data, true);
        if (count($data['data']['openid']) < 10000) {
            foreach ($data['data']['openid'] as $val) {
                $user = $wechat->user->get($val);
                $user = json_decode($user, true);
                if (!(DB::table('wechat_users')->where('id', $user['openid'])->count())) {
                    WechatUser::insertData($user['headimgurl'], $user['nickname'], $user['remark'], $user['sex'], $user['language'], $user['country'], $user['province'], $user['city'], date('Y-m-d H:i:s', $user['subscribe_time']), $user['openid']);
                } else {
                    DB::table('wechat_users')->where('id', $user['openid'])->update(['headimgurl' => $user['headimgurl'], 'nickname' => $user['nickname'], 'remark' => $user['remark'], 'sex' => $user['sex'], 'language' => $user['language'], 'country' => $user['country'], 'province' => $user['province'], 'city' => $user['city'], 'subscribe_at' => date('Y-m-d H:i:s', $user['subscribe_time'])]);
                }
            }
        } else {
            $this->synchro($data['next_openid']);
        }
    }
}
