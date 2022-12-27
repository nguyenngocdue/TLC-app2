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
        $this->loadMigrationsFrom($path . "thirdParties");
        $this->loadMigrationsFrom($path . "unitTests");

        $this->loadMigrationsFrom($path . "entities/system");
        $this->loadMigrationsFrom($path . "entities/global");
        $this->loadMigrationsFrom($path . "entities/user");
        $this->loadMigrationsFrom($path . "entities/production");
        $this->loadMigrationsFrom($path . "entities/qaqc");

        $this->loadMigrationsFrom($path . "pivots");
        $this->loadMigrationsFrom($path . "foreignKeys");
    }
}
