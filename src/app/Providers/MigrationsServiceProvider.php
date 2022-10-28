<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MigrationsServiceProvider extends ServiceProvider
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
        $path = "database/migrations/";
        $this->loadMigrationsFrom($path . "entities");
        $this->loadMigrationsFrom($path . "production");
        $this->loadMigrationsFrom($path . "unittests");

        $this->loadMigrationsFrom($path . "pivots");
        $this->loadMigrationsFrom($path . "foreignkeys");
    }
}
