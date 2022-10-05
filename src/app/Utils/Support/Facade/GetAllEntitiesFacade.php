<?php

namespace App\Utils\Support\Facade;

use Illuminate\Support\Facades\Facade;

class GetAllEntitiesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'getAllEntities';
    }
}
