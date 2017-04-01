<?php

namespace App;

use EasyWeChat\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

/**
 * App\WechatStatistics
 *
 * @mixin \Eloquent
 */
class WechatStatistics extends Model
{
    protected $primaryKey = 'id';

    protected $keyType = 'int';
    private $wechat;

    public function __construct(array $attributes = [])
    {
        $this->wechat = new Application(config('wechat'));
        parent::__construct($attributes);
    }
    public function scopeUserList()
    {
        $time=time ()- ( 1  *  24  *  60  *  60 );
        $time1=time ()- ( 7  *  24  *  60  *  60 );
        $list = $this->wechat->stats->userCumulate(date('Y-m-d', $time1),date('Y-m-d', $time));
        $list1 = json_decode($list, true);
        $data1 = [];
        foreach ($list1['list'] as $val){
            array_push($data1,$val['cumulate_user']);
        }
        $dataList['list0'] = $data1;


        $dateList = [];
        for ($i = 7; $i >= 1; $i--) {
            $time = time ()- ( $i  *  24  *  60  *  60 );
            $dateList[date('Y-m-d', $time)] = 0;
        }
        $list2 = $this->wechat->stats->userSummary(date('Y-m-d', $time1),date('Y-m-d', $time));
        $data2 = json_decode($list2, true);
        foreach ($data2['list'] as $val){
            if (isset($dateList[$val['ref_date']]) and $val['new_user']){
                $dateList[$val['ref_date']] += $val['new_user'];
            }
        }
        $dataList['list1'] = array_values($dateList);


        $dateList1 = [];
        for ($i = 7; $i >= 1; $i--) {
            $time = time ()- ( $i  *  24  *  60  *  60 );
            $dateList1[date('Y-m-d', $time)] = 0;
        }
        $list3 = $this->wechat->stats->userSummary(date('Y-m-d', $time1),date('Y-m-d', $time));
        $data3 = json_decode($list3, true);
        foreach ($data3['list'] as $val){
            if (isset($dateList1[$val['ref_date']]) and $val['cancel_user']){
                $dateList1[$val['ref_date']]++;
            }
        }
        $dataList['list2'] = array_values($dateList1);


        $dateList2 = [];
        for ($i = 7; $i >= 1; $i--) {
            $time = time ()- ( $i  *  24  *  60  *  60 );
            $dateList2[date('Y-m-d', $time)] = 0;
        }
        $list4 = $this->wechat->stats->upstreamMessageSummary(date('Y-m-d', $time1),date('Y-m-d', $time));
        $data4 = json_decode($list4, true);
        foreach ($data4['list'] as $val){
            if (isset($dateList2[$val['ref_date']])){
                $dateList2[$val['ref_date']] = $val['msg_count'];
            }
        }
        $dataList['list3'] = array_values($dateList2);


        $dateList3 = [];
        for ($i = 7; $i >= 1; $i--) {
            $time = time ()- ( $i  *  24  *  60  *  60 );
            $dateList3[date('Y-m-d', $time)] = 0;
        }
        $list5 = $this->wechat->stats->userShareSummary(date('Y-m-d', $time1),date('Y-m-d', $time));
        $data5 = json_decode($list5, true);
        foreach ($data5['list'] as $val){
            if (isset($dateList3[$val['ref_date']])){
                $dateList3[$val['ref_date']] = $val['share_count'];
            }
        }
        $dataList['list4'] = array_values($dateList3);



        return $dataList;
    }
//
    public function scopeSourceList(){
        $time=time ()- ( 1  *  24  *  60  *  60 );
        $time1=time ()- ( 7  *  24  *  60  *  60 );
        $list = $this->wechat->stats->userSummary(date('Y-m-d', $time1),date('Y-m-d', $time));
        $data = json_decode($list, true);
        return $data['list'];
    }
    public function scopeSexList(){
        $list = $this->wechat->user->lists();
        $data = json_decode($list, true);
        $userInfoList = [];
        foreach ($data['data']['openid'] as $val){
            $userInfo =json_decode($this->wechat->user->get($val),true);
            array_push($userInfoList,$userInfo);
        }
        return $userInfoList;
    }

}
