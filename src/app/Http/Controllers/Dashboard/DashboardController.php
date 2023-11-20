<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    protected function getUserSettings()
    {
        $settings = CurrentUser::getSettings();
        $array = $settings['qaqc_insp_chklst_shts'][Constant::VIEW_ALL]['dashboard_matrix'] ?? [];
        $sub_project_id = $array['sub_project_id'] ?? null;
        $prod_routing_id = $array['prod_routing_id'] ?? null;
        $qaqc_insp_tmpl_id = $array['qaqc_insp_tmpl_id'] ?? null;

        return [$sub_project_id, $prod_routing_id, $qaqc_insp_tmpl_id];
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
