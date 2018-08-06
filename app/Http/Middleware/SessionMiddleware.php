<?php

namespace App\Http\Middleware;

class SessionMiddleware
{
    public function handle($request, $next)
    {
        $redisSession = new \App\Lib\RedisSession();

        session_set_cookie_params(3600*24);

        session_set_save_handler($redisSession, true);

        session_start();

        return $next($request);
    }
}