<?php

namespace App\Http\Middleware;

use Helper;

class CsrfMiddleware
{
    private $whiteList = [

    ];

    public function __construct()
    {

    }

    public function handle($requst, $next)
    {
        $url = $requres->path();

        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

        if (!in_array($url, $this->whiteList)) {
            if (!isset($_SESSION['token']) || empty($token) || $token != $_SESSION['token']) {
                return Helper::sendJson(402, 'token校验失效');
            }
        }

        return $next($requst);
    }
}