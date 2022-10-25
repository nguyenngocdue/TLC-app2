<?php

namespace App\Providers;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\ServiceProvider;

class CurrentUserFacadeProvider extends ServiceProvider
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
        $this->app->singleton('currentUser', function () {
            return new CurrentUser();
        });
    }
}
