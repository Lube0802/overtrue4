<?php

namespace App\Http\Middleware;

use Closure;
use App\Lib\AuthUser;
use Helper;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */

    /**
     * 白名单
     * @var array
     */
    private $whiteList = [

    ];


    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $uri = $request->path();

        if (!in_array($uri, $this->whiteList)) {
            if (!AuthUser::check()) {
                return Helper::sendJson(401, '需要登录');
            }
        }

        return $next($request);
    }
}
