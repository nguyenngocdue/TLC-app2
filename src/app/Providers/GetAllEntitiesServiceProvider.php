<?php

namespace App\Providers;

use App\Utils\Support\GetAllEntities;
use Illuminate\Support\ServiceProvider;

class GetAllEntitiesServiceProvider extends ServiceProvider
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
        $this->app->singleton('getAllEntities', function () {
            return new GetAllEntities();
        });
    }
}
