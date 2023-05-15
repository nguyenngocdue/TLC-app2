<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class AppFooter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function accessLog()
    {
        $cuId = CurrentUser::id();
        $took = Timer::getTimeElapse();
        $routeName = CurrentRoute::getName();
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        DB::connection($connection)->table('logger_access')->insert([
            'owner_id' => $cuId,
            'took' => $took,
            'route_name' => $routeName,
            'url' => url()->current(),
            'env' => env('APP_ENV'),
        ]);
        // if (env('SHOW_ACCESS_LOGGER')) {
        //     echo " - CU: $cuId";
        //     echo " - Elapse: $took";
        //     echo " - RouteName: $routeName";
        // }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->accessLog();

        return view('components.renderer.app-footer');
    }
}
