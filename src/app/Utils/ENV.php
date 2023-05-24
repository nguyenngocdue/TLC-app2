<?php

namespace App\Utils;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;

class ENV
{
    public static function present()
    {
        return App::isTesting() || App::isLocal() || CurrentUser::isAdmin();
    }
}
