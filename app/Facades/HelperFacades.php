<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class HelperFacades extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Helper';
    }
}