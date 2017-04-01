<?php

namespace App\Http\Controllers;

use App\Article;
use App\WechatMessage;
use App\Tag;
use App\WechatUser;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;


class WechatController extends Controller
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        // Log::info('request arrived.');
        // 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        // $wechat = app('wechat');
        $wechat = new Application(config('wechat'));

        // services...
        $server = $wechat->server;
        // $user = $wechat->user;
        // $oauth = $wechat->oauth;

        $server->setMessageHandler(function ($message) {
            $wechat = new Application(config('wechat'));
            $users = $wechat->user->get($message->FromUserName);
            $users = json_decode($users,true);
//            $tags = '';
//            foreach ($users['tagid_list'] as $val){
//                $tags .= $val . ',';
//            }
//            $tags = rtrim($tags,',');
            $createTime = date('Y-m-d H:i:s',$message->CreateTime);
            switch ($message->MsgType) {
                case 'event':

                    switch ($message->Event) {
                        case 'subscribe':
                            WechatMessage::insetData(123,'关注事件','关注',$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                            WechatUser::insertData($users['headimgurl'],$users['nickname'],$users['remark'],$users['sex'],$users['language'],$users['country'],$users['province'],$users['city'],$createTime,$users['openid']);
                            return "欢迎关注 DNA Speaking。";
                            break;
                        case 'unsubscribe':
                            WechatUser::unsubscribe($users['openid']);
                            WechatMessage::insetData($message->MsgId,'取消关注事件','取关',$users['openid'],'','',date('Y-m-d H:i:s'));
                            return '我们会努力赢得您的再次关注。';
                            break;
                        default:
                            WechatMessage::insetData($message->MsgId,'关注事件','关注',$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                            return "欢迎关注 DNA Speaking。";
                            break;
                    }
                case 'text':
                    WechatMessage::insetData($message->MsgId,'文本消息',$message->Content,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    // 文本消息内容
                    $text = $message->Content;
                    $tag = Tag::where('name', $text)->first();
                    if ($tag) {
                        $articles = $tag->articles()->get();

                    } else {
                        $articles = Article::search($text)->get();
                    }
                    $news_list = [];
                    foreach ($articles as $article) {
                        $news_list[] = new News(
                            [
                                'title' => $article->title,
                                'description' => $article->abstract,
                                'url' => 'http://www.baidu.com',
                                'image' => 'http://dnaspeaking.com/images/public.jpg',
                            ]
                        );
                    }

                    if ($news_list) {
                        return $news_list;
                    } else {
                        return '您可以发送关键词来获取最近文章。';
                    }
                    break;
                case 'image':
                    WechatMessage::insetData($message->MsgId,'图片消息',$message->PicUrl,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return '您发送的是一条图片信息，您可以发送关键词来获取最近文章。';
                    break;
                case 'voice':
                    WechatMessage::insetData($message->MsgId,'语音消息',$message->MediaId,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return '您发送的是一条语音信息，您可以发送关键词来获取最近文章。';
                    break;
                case 'video':
                    WechatMessage::insetData($message->MsgId,'视频消息',$message->MediaId,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return '您发送的是一条视频信息，您可以发送关键词来获取最近文章。';
                    break;
                case 'location':
                    WechatMessage::insetData($message->MsgId,'坐标消息',$message->Label,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return '您发送的是一条坐标信息，您可以发送关键词来获取最近文章。';
                    break;
                case 'link':
                    WechatMessage::insetData($message->MsgId,'链接消息',$message->Url,$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return '您发送的是一条链接信息，您可以发送关键词来获取最近文章。';
                    break;
                // ... 其它消息
                default:
                    WechatMessage::insetData($message->MsgId,'未知消息','',$users['openid'],$users['headimgurl'],$users['nickname'],$createTime);
                    return "欢迎关注 DNA Speaking。";
                    break;
            }
        });

        // Log::info('return response.');

        return $wechat->server->serve();
    }
}