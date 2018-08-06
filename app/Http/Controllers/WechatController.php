<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;

class WechatController extends BaseController
{
    public function index()
    {
        $app = Factory::officialAccount(config('wechat'));
        return $app->server->serve();
    }
}