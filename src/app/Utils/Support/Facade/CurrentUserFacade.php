<?php

namespace App\Utils\Support\Facade;

use Illuminate\Support\Facades\Facade;

class CurrentUserFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'currentUser';
    }
}
