<?php

namespace App\Providers;

use App\Console\Commands\CreateControllerEntity\CreateControllerEntityCreator;
use App\Console\Commands\CreateTableRelationship\MigrationRelationShipCreator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(MigrationRelationShipCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });
        $this->app->when(CreateControllerEntityCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });


        include_once(__DIR__ . "/Macro/Str.php");
        include_once(__DIR__ . "/Macro/Arr.php");
        include_once(__DIR__ . "/Macro/App.php");
    }
}
