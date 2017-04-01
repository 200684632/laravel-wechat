<?php

namespace App\Admin\Controllers;

use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Box;
use App\WechatUser;
use App\WechatStatistics;
use App\WechatTag;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Tab;

class WechatStatisticsController extends Controller
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
//            $res = ViewStatistics::where(['post_id' => -1, 'time_type' => 'day', 'data_key' => 'pv'])->where('time_index', '>=', '20170101')->where('time_index', '<', '20170215')->get(['route', 'time_index', 'data_value']);
//            $data = array();
//            foreach ($res as $val) {
//                $data[$val->route]['time_index'][] = $val->time_index;
//                $data[$val->route]['data_value'][] = $val->data_value;
//            }


            $content->header('微信');
            $content->description('统计信息');
            $tab = new Tab();
            $content->row(function ($row) {
                $row->column(6, new InfoBox('微信用户总数', 'wechat_users', 'aqua', '/admin/wechat_users', WechatUser::users()));
                $row->column(6, new InfoBox('标签总数', 'wechat_tags', 'green', '/admin/wechat_tags', WechatTag::tags()));
            });
////
//            // 更多示例
//            // http://echarts.baidu.com/examples.html
////            $content->row(new Box('', view('echarts.test')));
//
            $name_list = ['用户总数', '日增长人数', '日减少人数', '消息发送次数', '图文分享转发次数'];
            $time_index_list = [];
            for ($i = 1; $i <= 7; $i++) {
                array_unshift($time_index_list, (int)date('Ymd', strtotime("- {$i} day")));
            }
            $userList = WechatStatistics::userList();
            $container = [];
            foreach ($name_list as $key => $val) {
                $container[$val] = $userList['list' . $key];
            }
//
//
//            // 全站流量
            $allFlow = new Box('微信统计', view('echarts.view_wechat', ['name_list' => array_values($name_list), 'time_index_list' => $time_index_list, 'container' => $container]));
            $title_list = ['其他','公众号搜索', '名片分享', '扫描二维码 ', '图文页右上角菜单', '支付后关注', '图文页内公众号名称', '公众号文章广告', '朋友圈广告'];

            $chart_data_list = [
                                '其他' => 0,
                                '公众号搜索' => 0,
                                '名片分享' => 0,
                                '扫描二维码' => 0,
                                '图文页右上角菜单' => 0,
                                '支付后关注' => 0,
                                '图文页内公众号名称' => 0,
                                '公众号文章广告' => 0,
                                '朋友圈广告' => 0,
                                ];
            $sourceList = WechatStatistics::sourceList();
            foreach ($sourceList as $key=>$val){
                if ($val['user_source'] == 0){
                    $sourceList[$key]['user_source'] = '其他';
                }elseif ($val['user_source'] == 1){
                    $sourceList[$key]['user_source'] = '公众号搜索';
                }elseif ($val['user_source'] == 17){
                    $sourceList[$key]['user_source'] = '名片分享';
                }elseif ($val['user_source'] == 30){
                    $sourceList[$key]['user_source'] = '扫描二维码';
                }elseif ($val['user_source'] == 43){
                    $sourceList[$key]['user_source'] = '图文页右上角菜单';
                }elseif ($val['user_source'] == 51){
                    $sourceList[$key]['user_source'] = '支付后关注';
                }elseif ($val['user_source'] == 57){
                    $sourceList[$key]['user_source'] = '图文页内公众号名称';
                }elseif ($val['user_source'] == 75){
                    $sourceList[$key]['user_source'] = '公众号文章广告';
                }elseif ($val['user_source'] == 78){
                    $sourceList[$key]['user_source'] = '朋友圈广告';
                }
            }
            foreach ($sourceList as $value){
                if (isset($chart_data_list[$value['user_source']])){
                    $chart_data_list[$value['user_source']] += $value['new_user'];
                }

            }
            $userSource = new Box('用户来源', view('echarts.view_chart', ['title_list' => $title_list,'data'=>$chart_data_list]));
            $content->row($allFlow);
            $content->row($userSource);
            $sexList = WechatStatistics::sexList();
            $sex = ['男','女','未知'];
            $sex_data_list = ['男'=>0,'女'=>0,'未知'=>0];
            foreach ($sexList as $val){
                if ($val['sex'] == 1){
                    $sex_data_list['男']++;
                }elseif ($val['sex'] == 2){
                    $sex_data_list['女']++;
                }else{
                    $sex_data_list['未知']++;
                }
            }
            $sexProportion = new box('男女比例',view('echarts.view_attr', ['title_list' => $sex,'data'=>$sex_data_list]));
            $content->row($sexProportion);
            $provinceList = [
                '天津'=>0,'上海'=>0,'重庆'=>0,'河北'=>0,'山西'=>0,'辽宁'=>0,'吉林'=>0,
                '黑龙江'=>0,'江苏'=>0,'浙江'=>0,'安徽'=>0,'福建'=>0,'江西'=>0,'山东'=>0,
                '河南'=>0,'湖北'=>0,'湖南'=>0,'广东'=>0,'海南'=>0,'四川'=>0,'贵州'=>0,
                '云南'=>0,'陕西'=>0,'甘肃'=>0,'青海'=>0,'台湾'=>0,'内蒙古'=>0,'广西'=>0,
                '西藏'=>0,'宁夏'=>0,'新疆'=>0,'香港'=>0,'澳门'=>0,'未知'=>0
            ];
            foreach ($sexList as $value){
                if (isset($provinceList[$value['province']])){
                    $provinceList[$value['province']]++;
                }else{
                    $provinceList['未知']++;
                }
            }
            $sexProportion = new box('人员分布',view('echarts.view_person', ['name_list'=>array_keys($provinceList),'data'=>$provinceList]));
            $content->row($sexProportion);
            $content->row($tab);

        });
    }
}
