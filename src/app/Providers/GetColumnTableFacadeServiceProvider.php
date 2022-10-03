<?php

namespace App\Providers;

use App\GetColumnTable\GetColumnTable;
use Illuminate\Support\ServiceProvider;

class GetColumnTableFacadeServiceProvider extends ServiceProvider
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
        $this->app->singleton('getColumnTable', function () {
            return new GetColumnTable();
        });
    }
}
