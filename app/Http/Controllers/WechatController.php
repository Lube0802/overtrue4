<?php

namespace App\Http\Controllers;

use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Voice;
use Helper;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Input;

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

    /**
     * 事件消息
     * subscribe 关注 unsubscribe 取消关注 LOCATION 上报地理位置
     * @param $message
     * @return string
     */
    public function doEvent($message)
    {
        switch ($message['Event']) {
            // 关注事件
            case 'subscribe':
                return $this->eventSubscribe($message);
                break;
            // 取消关注事件
            case 'unsubscribe':
                return $this->eventUnSubscribe($message);
                break;
            // 上报地理位置事件
            case 'LOCATION':
                return $this->eventLocation($message);
                break;
        }

        // 自定义菜单事件
        switch ($message['EventKey']) {
            case 'V1001_GOOD':
                return $this->eventLike($message);
                break;
        }
    }

    /**
     * 关注事件
     * @param $message
     * @return Text
     */
    public function eventSubscribe($message)
    {
        $redis = app('redis');

        if (isset($message['EventKey'])) {
            $key = preg_replace('/qrscene_/', '', $message['EventKey']);
            $redis->hset('test:subscribe-key', $message['FromUserName'], $key);
        }

        return new Text(config('wechat')['welcome']);
    }

    /**
     * 取关事件
     * @param $message
     */
    public function eventUnSubscribe($message)
    {
        $redis = app('redis');

        $redis->hdel('test:subscribe-key', $message['FromUserName']);

        return;
    }

    /**
     * 上报地理位置事件
     * @param $message
     * @return Text
     */
    public function eventLocation($message)
    {
        $latitude = $message['Latitude']; // 纬度
        $longitude = $message['Longitude']; // 经度
        $precision = $message['Precision']; // 精度

        return new Text("经度：{$latitude}\n纬度：{$longitude}\n精度：{$precision}\n");
    }

    /**
     * 自定义菜单事件
     * @param $message
     * @return Text
     */
    public function eventLike($message)
    {
        return new Text("EventKey：".$message['EventKey']);
    }

    /**
     * 文字消息
     * @param $message
     * @return Text
     */
    public function doText($message)
    {
        return new Text('你好，'.$message['FromUserName']);
    }

    /**
     * 图片消息
     * @param $message
     * @return Image
     */
    public function doImage($message)
    {
        return new Image('CCEgqXsZHPDFicPpIfGKejcXErUFgM8M3SnU8Emuzc1a8eDrfDiJlXhvyMI5oDvB');
    }

    /**
     * 语音消息
     * @param $message
     * @return Voice
     */
    public function doVoice($message)
    {
        return new Voice('QyLUrahfNoDxgeCW7yzoPpDcYA8fFTWLxfbBUoDOrX89uUxRlpTQxvrHKtH_g3ry');
    }

    public function doVideo($message)
    {
        // 回复图文消息
        $items = [
            new NewsItem([
                'title' => '标题1',
                'descriptiom' => '描述1',
                'url' => 'http://www.baidu.com',
                'image' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/imgs/wx/1.jpg',
            ]),
            new NewsItem([
                'title' => '标题2',
                'descriptiom' => '描述2',
                'url' => 'http://www.baidu.com',
                'image' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/imgs/wx/2.jpg',
            ]),
            new NewsItem([
                'title' => '标题3',
                'descriptiom' => '描述3',
                'url' => 'http://www.baidu.com',
                'image' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/imgs/wx/3.jpg',
            ]),
            new NewsItem([
                'title' => '标题4',
                'descriptiom' => '描述4',
                'url' => 'http://www.baidu.com',
                'image' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/imgs/wx/4.jpg',
            ]),
        ];

        return new News($items);
    }

    public function doLocation($message)
    {
        $msg = "你的位置：".
            "\n经度：".$message['Location_Y'].
            "\n纬度：".$message['Location_X'].
            "\n位置：".$message['Label'];

        return new Text($msg);
    }

    public function doLink($message)
    {
        $msg = "标题：".$message['Title'].
            "\n描述：".$message['Description'].
            "\n链接：".$message['Url'];

        return new Text($msg);
    }

    public function doFile($message)
    {
        return '文件消息';
    }

    public function setMenu()
    {
        $menu = config('wechat')['menu'];
        $app = Factory::officialAccount(config('wechat'));
        $app->menu->create($menu);
        return "<h1>The menu is created</h1>";
    }

    public function updMenu()
    {
        $menu = config('wechat')['menu'];
        $app = Factory::officialAccount(config('wechat'));
        $app->menu->delete();
        $app->menu->create($menu);
        return "<h1>Menu has updated</h1>";
    }

    public function showMenu()
    {
        $app = Factory::officialAccount(config('wechat'));
        $list = $app->menu->list();
        $current = $app->menu->current();
        var_dump($list, $current);
    }

    public function getWechatParam($url = '')
    {
        $url = urldecode(Input::get('url', ''));

        $app = Factory::officialAccount(config('wechat'));

        if (!empty($url)) {
            $app->jssdk->setUrl($url);
        }

        $param = $app->jssdk->buildConfig(config('js_sdk'), $debug = false, $beta = false, $json = false);

        return Helper::sendJson(200, 'ok', $param);
    }
}