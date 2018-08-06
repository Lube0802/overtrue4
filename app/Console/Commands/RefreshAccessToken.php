<?php

namespace App\Console\Commands;

use Helper;
use EasyWeChat\Factory;
use Illuminate\Console\Command;

class RefreshAccessToken extends Command
{
    protected $signature = 'refreshtoken';

    protected $description = 'refreshtoken';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $app = Factory::officialAccount(config('wechat'));
        $access_token = $app->access_token->getToken(true); // 返回数组
        Helper::log('accesstoken', $access_token['access_token']);
        $app['access_token']->setToken($access_token['access_token']);
    }
}