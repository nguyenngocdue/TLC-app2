<?php

namespace App\Providers;

use App\Console\CreateControllerEntity\CreateControllerEntityCreator;
use App\Console\CreateTableRelationship\MigrationRelationShipCreator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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

        Str::macro('pretty', function (string $value) {
            return Str::title(Str::replace("_", " ", $value));
        });
    }
}
