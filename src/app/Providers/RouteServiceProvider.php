<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const DASHBOARD = "/";

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            // Route::prefix("api")
            //     ->middleware("api")
            //     ->namespace($this->namespace)
            //     ->group(base_path("routes/api.php"));

            // Route::middleware("web")
            //     ->namespace($this->namespace)
            //     ->group(base_path("routes/web.php"));

            //This can cache the routes, but the timer will not work properly.

            Route::prefix("api")
                ->middleware("api")
                ->namespace($this->namespace)
                ->group(base_path("routes/api.php"))
                ->group(base_path("routes/api-auth.php"))
                ->group(base_path("routes/api-entity.php"))
                ->group(base_path("routes/api-hr.php"))
                ->group(base_path("routes/api-prod.php"))
                ->group(base_path("routes/api-qaqc.php"))
                ->group(base_path("routes/api-system.php"));

            Route::middleware("web")
                ->namespace($this->namespace)
                ->group(base_path("routes/web.php"))
                ->group(base_path("routes/web-auth.php"))
                ->group(base_path("routes/web-backward-compatible.php"))
                ->group(base_path("routes/web-manage-workflows.php"))
                ->group(base_path("routes/web-manage-libs.php"))
                ->group(base_path("routes/web-dashboard.php"))
                ->group(base_path("routes/web-global.php"))
                ->group(base_path("routes/web-database.php"))
                ->group(base_path("routes/web-guest.php"))
                ->group(base_path("routes/web-permission.php"))
                ->group(base_path("routes/web-report.php"))
                ->group(base_path("routes/web-pivot-report.php"))
                ->group(base_path("routes/web-exam-question.php"))
                ->group(base_path("routes/web-diginet.php"))
                ->group(base_path("routes/api-diginet.php"));

            // if ($this->app->request->is('api/*')) {
            //     Route::prefix("api")
            //         ->middleware("api")
            //         ->namespace($this->namespace)
            //         ->group(base_path("routes/api.php"));
            // } else {
            //     Route::middleware("web")
            //         ->namespace($this->namespace)
            //         ->group(base_path("routes/web.php"));

            //     Route::prefix("api")
            //         ->middleware("api")
            //         ->namespace($this->namespace)
            //         ->group(base_path("routes/api.php"));
            // }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for("api", function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip(),
            );
        });
    }
}
