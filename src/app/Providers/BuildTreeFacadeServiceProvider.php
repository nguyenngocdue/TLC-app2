<?php

namespace App\Providers;

use App\Utils\Support\Entities;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\ServiceProvider;

class BuildTreeFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('buildTree', function () {
            return new BuildTree();
        });
    }
}
