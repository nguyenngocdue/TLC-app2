<?php

namespace App\Providers;

use App\Console\Commands\CreateControllerEntity\CreateControllerEntityCreator;
use App\Console\Commands\CreateTableRelationship\MigrationRelationShipCreator;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // singleton Services
        $this->app->singleton(CommentServiceInterface::class, CommentService::class);


        // singleton Repositories
        $this->app->singleton(CommentRepositoryInterface::class, CommentRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->when(MigrationRelationShipCreator::class)
        //     ->needs('$customStubPath')
        //     ->give(function ($app) {
        //         return $app->basePath('stubs');
        //     });
        // $this->app->when(CreateControllerEntityCreator::class)
        //     ->needs('$customStubPath')
        //     ->give(function ($app) {
        //         return $app->basePath('stubs');
        //     });


        include_once(__DIR__ . "/Macro/Str.php");
        include_once(__DIR__ . "/Macro/Arr.php");
        include_once(__DIR__ . "/Macro/App.php");
    }
}
