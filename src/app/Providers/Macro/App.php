<?php

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;

//isLocal --> laravel has this function by default

App::macro('isTesting', function () {
    return env('APP_ENV') == "testing";
});
App::macro('isProduction', function () {
    return env('APP_ENV') == "production";
});

App::macro('present', function () {
    return App::isTesting() || App::isLocal() || CurrentUser::isAdmin();
});
