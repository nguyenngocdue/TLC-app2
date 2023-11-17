<?php

namespace App\Http\Controllers;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getType()
    {
        return "dashboard";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $params = [];
        $subView = CurrentUser::getViewSuffix();
        switch ($subView) {
            case Constant::DASHBOARD_EXTERNAL_INSPECTOR:

                $params = [
                    'projectId' => 72,
                    'subProjectId' => 112,
                    'prodRoutingId' => 49,
                ];
                break;
        }
        $view = "dashboard$subView";
        return view('dashboards.' . $view, $params);
    }
}
