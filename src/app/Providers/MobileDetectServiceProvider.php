<?php

namespace App\Providers;

use Detection\MobileDetect;
use Illuminate\Support\ServiceProvider;

class MobileDetectServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
        $this->app->singleton('MobileDetect', function ($app) {
            return new MobileDetect();
        });
    }

    public function boot()
    {
        //
    }
}
