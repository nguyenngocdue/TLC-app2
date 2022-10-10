<?php

namespace App\Providers;

use App\Utils\Support\Entities;
use Illuminate\Support\ServiceProvider;

class EntitiesFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Entities', function () {
            return new Entities();
        });
    }
}
