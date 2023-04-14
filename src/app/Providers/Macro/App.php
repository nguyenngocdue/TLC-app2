<?php

use Illuminate\Support\Facades\App;

App::macro('isTesting', function () {
    return env('APP_ENV') == "testing";
});
App::macro('isProduction', function () {
    return env('APP_ENV') == "production";
});
