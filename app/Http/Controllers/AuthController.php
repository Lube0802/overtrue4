<?php

namespace App\Http\Controllers;

use App\Lib\AuthUser;
use App\Models\Member;
use Helper;
use Illuminate\Support\Facades\Input;

class AuthController extends BaseController
{
    public function debug()
    {
        if (env('APP_DEBUG') == true) {
            $id = trim(Input::get('id'));

            if (!AuthUser::check()) {
                if (empty($id) || !is_numeric($id) || $id <= 0) {
                    return Helper::sendJson(204, '参数错误');
                }

                $userData = Member::getUserInfo(['id' => $id]);

                if (empty($userData)) {
                    return Helper::sendJson(203, '没有该用户');
                }

                AuthUser::login($userData);

                return Helper::sendJson(200, 'ok', $userData);
            } else {
                return Helper::sendJson(202, '已经登录');
            }
        } else {
            return Helper::sendJson(507, '非法操作');
        }
    }

    public function logout()
    {
        if (AuthUser::check()) {
            AuthUser::forgot();

            return Helper::sendJson(200, 'success');
        } else {
            return Helper::sendJson(201, 'no login');
        }
    }
}