<?php

namespace App\Http\Middleware;

class TokenMiddleware
{
    public function handle($request, $next)
    {
        $response = $next($request);

        $token = sha1(uniqid('', true).str_random(40).microtime(true)).md5(uniqid('', true).str_random(40).microtime(true));

        setcookie("token", $token, time()+3600*24, "/", env("COOKIEDOMAIN"));

        $_SESSION["token"] = $token;

        return $response;
    }
}