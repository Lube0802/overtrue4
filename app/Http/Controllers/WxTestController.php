<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Helper;
use App\Lib\AuthUser;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Input;
use Overtrue\Socialite\AuthorizeFailedException;

class WxTestController extends BaseController
{
    public function login()
    {
        $return_url = trim(Input::get('return_url'));

        if (!$return_url && array_key_exists('HTTP_REFERER', $_SERVER)) {
            $return_url = $_SERVER['HTTP_REFERER'];
        } elseif (!$return_url && !array_key_exists('HTTP_REFERER', $_SERVER)) {
            $return_url = AuthUser::take('return_url') ? AuthUser::take('return_url') : '/';
        }

        if (!AuthUser::check()) {
            AuthUser::add('return_url', $return_url);
            $app = Factory::officialAccount(config('wechat'));
            $oauth = $app->oauth;
            return $oauth->redirect();
        } else {
            if (!$return_url) {
                $return_url = '/';
            }
            AuthUser::del('return_url');
            return redirect($return_url);
        }
    }

    public function loginCallback()
    {
        $code = trim(Input::get('code'));
        $state = trim(Input::get('state'));

        return view('wechat.logincallback', ['code' => $code, 'state' => $state]);
    }

    public function loginRedirect()
    {
        try {
            $code = trim(Input::get('code'));
            $state = trim(Input::get('state'));
            if (!$code || !$state) {
                return Helper::sendJson(509, '缺少参数');
            }

            $wechat_info = AuthUser::take('wechat_info');
            if (!$wechat_info || !$wechat_info->getId()) {
                $app = Factory::officialAccount(config('wechat'));
                $oauth = $app->oauth;
                $wechat_info = $oauth->user();
                if (!$wechat_info || !$wechat_info->getId()) {
                    throw new AuthorizeFailedException('', '');
                }
                AuthUser::add('wechat_info', $wechat_info);
            }
            if (!$wechat_info || !$wechat_info->getId()) {
                throw new \Exception('get wechat info error');
            }

            $return_url = AuthUser::take('return_url');
            if (!$return_url) {
                $return_url = '/';
            }

            $data = [
                'openid' => $wechat_info->getId(),
            ];

            $user_data = Member::loginByUnionId($data['openid']);
            if (!$user_data) {
                AuthUser::add('openid', $data['openid']);
            } else {
                AuthUser::login($user_data);
            }

            return Helper::sendJson(200, 'ok', ['url' => $return_url]);
        } catch (\Exception $e) {
            AuthUser::del('wechat_info');
            Helper::log('wechatLoginError', $e->getMessage()."\n".$e->getTraceAsString());

            return redirect('/api/login');
        }
    }
}