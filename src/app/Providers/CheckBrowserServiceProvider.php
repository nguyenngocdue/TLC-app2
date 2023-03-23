<?php

namespace App\Providers;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CheckBrowserServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        if (preg_match('/Edg/i', $_SERVER['HTTP_USER_AGENT'])) {
            abort(403, 'The app doesn\'t support this browser yet');
        }
    }
}
