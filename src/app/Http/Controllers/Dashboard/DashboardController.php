<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $controller = CurrentUser::getViewSuffix();
        switch ($controller) {
            case "-external-inspector":
                return (new DashboardExternalInspectorController())->index($request);
            case "":
                return view('dashboards.dashboard', []);
            default:
                return $controller . " is not a registered dashboard.";
        }
    }
}
