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
        $this->loadMigrationsFrom($path . "entities/project");
        $this->loadMigrationsFrom($path . "entities/qaqc");
        $this->loadMigrationsFrom($path . "entities/hse");
        $this->loadMigrationsFrom($path . "entities/ghg");
        $this->loadMigrationsFrom($path . "entities/esg");
        $this->loadMigrationsFrom($path . "entities/eco");
        $this->loadMigrationsFrom($path . "entities/hr");
        $this->loadMigrationsFrom($path . "entities/exam");
        // $this->loadMigrationsFrom($path . "entities/it");
        $this->loadMigrationsFrom($path . "entities/act");
        $this->loadMigrationsFrom($path . "entities/kanban");
        $this->loadMigrationsFrom($path . "entities/crm");
        // $this->loadMigrationsFrom($path . "dataWarehouses");

        $this->loadMigrationsFrom($path . "pivots");
        $this->loadMigrationsFrom($path . "foreignKeys");

        $this->loadMigrationsFrom($path . "views");
        // $this->loadMigrationsFrom($path . "storedProcedures");
        // $this->loadMigrationsFrom($path . "storedFunctions");

        $this->loadMigrationsFrom($path . "diginets");
    }
}
