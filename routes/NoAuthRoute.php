<?php

$app->get('login', ['as' => 'login', 'uses' => 'WxTestController@login']);
$app->get('login-callback', 'WxTestController@loginCallback');
$app->get('login-redirect', 'WxTestController@loginRedirect');

$app->get('test', 'TestController@test');
$app->get('wechat', 'WechatController@index');
$app->post('wechat', 'WechatController@index');
$app->get('debug', 'AuthController@debug');

$app->get('set-menu', 'WechatController@setMenu');
$app->get('upd-menu', 'WechatController@updMenu');
$app->get('wechat-param', 'WechatController@getWechatParam');
