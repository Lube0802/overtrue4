<?php

namespace App\Http\Controllers;

use Helper;

class TestController extends BaseController
{
    public function test()
    {
        echo "NoAuthRoute<br />";
        return Helper::sendJson(200, 'ok');
        print_r($_SESSION);
    }

    public function test2()
    {
        echo "AuthRoute<br />";
        print_r($_SESSION);
    }
}