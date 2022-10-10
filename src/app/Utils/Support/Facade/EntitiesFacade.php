<?php

namespace App\Utils\Support\Facade;

use Illuminate\Support\Facades\Facade;

class EntitiesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Entities';
    }
}
