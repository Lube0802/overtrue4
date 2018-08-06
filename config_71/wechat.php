<?php

return [
    'app_id' => 'wx4b86de063179211a',
    'secret' => '0c324dff567345fd32b616619b522396',

    'response_type' => 'array',

    'log' => [
        'level' => 'debug',
        'file' => storage_path('logs/wechatlog/wechat_'.date('Y-m-d').'.log'),
    ],

    'oauth' => [
        'scopes' => ['snsapi_userinfo'],
        'callback' => 'http://wx.lubetown.xin/api/login-callback',
    ],
];