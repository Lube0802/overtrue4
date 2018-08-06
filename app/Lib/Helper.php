<?php

namespace App\Lib;

/**
 * Class Helper
 * @package App\Lib
 */
class Helper
{
    public function sendJson($status = 200, $msg = '', $data = [])
    {
        return json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'sessionId' => session_id(),
        ], JSON_PRETTY_PRINT);
    }

    public function isWechat()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false) {
            return false;
        }
        return true;
    }

    public function wechatVersion()
    {
        preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $_SERVER['HTTP_USER_AGENT'], $matches);

        return $matches[2];
    }
}