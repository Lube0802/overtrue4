<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;

class WechatController extends BaseController
{
    public function index()
    {
        $app = Factory::officialAccount(config('wechat'));

        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                // 事件消息
                case 'event':
                    return $this->doEvent($message);
                    break;
                // 文字消息
                case 'text':
                    return $this->doText($message);
                    break;
                // 图片消息
                case 'image':
                    return $this->doImage($message);
                    break;
                // 语音消息
                case 'voice':
                    return $this->doVoice($message);
                    break;
                // 视频消息
                case 'video':
                    return $this->doVideo($message);
                    break;
                // 坐标消息
                case 'location':
                    return $this->doLocation($message);
                    break;
                // 链接消息
                case 'link':
                    return $this->doLink($message);
                    break;
                // 文件消息
                case 'file':
                    return $this->doFile($message);
                    break;
                default:
                    return '收到其他消息';
                    break;
            }
        });

        return $app->server->serve();
    }

    public function doEvent($message)
    {
        return '事件消息';
    }

    public function doText($message)
    {
        return '文字消息';
    }

    public function doImage($message)
    {
        return '图片消息';
    }

    public function doVoice($message)
    {
        return '语音消息';
    }

    public function doVideo($message)
    {
        return '视频消息';
    }

    public function doLocation($message)
    {
        return '坐标消息';
    }

    public function doLink($message)
    {
        return '链接消息';
    }

    public function doFile($message)
    {
        return '文件消息';
    }
}