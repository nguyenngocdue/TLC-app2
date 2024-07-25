<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MobileDetect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MobileDetect';
    }
}
