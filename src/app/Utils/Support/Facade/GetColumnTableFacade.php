<?php

namespace App\Utils\Support\Facade;

use Illuminate\Support\Facades\Facade;

class GetColumnTableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'getColumnTable';
    }
}
