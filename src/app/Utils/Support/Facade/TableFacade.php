<?php

namespace App\Utils\Support\Facade;

use Illuminate\Support\Facades\Facade;

class TableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Table';
    }
}
