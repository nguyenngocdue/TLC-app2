<?php

namespace App\Providers;

use App\Utils\Support\Table;
use Illuminate\Support\ServiceProvider;

class TableFacadeServiceProvider extends ServiceProvider
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
        $this->app->singleton('Table', function () {
            return new Table();
        });
    }
}
