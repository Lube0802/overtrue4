<?php

return [
    'app_id' => 'wx91cfe6f91573a977',
    'secret' => '59bfd8f7e02b7fe6fa08d3a3c8bf8c79',

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