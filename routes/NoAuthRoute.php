<?php

$app->get('test', 'TestController@test');
$app->get('wechat', 'WechatController@index');
$app->post('wechat', 'WechatController@index');

$app->get('login', ['as' => 'login', 'uses' => 'WxTestController@login']);
$app->get('login-callback', 'WxTestController@loginCallback');
$app->get('login-redirect', 'WxTestController@loginRedirect');