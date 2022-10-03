<?php

namespace App\GetColumn\Facade;

use Illuminate\Support\Facades\Facade;

class GetColumnTableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'getColumnTable';
    }
}
