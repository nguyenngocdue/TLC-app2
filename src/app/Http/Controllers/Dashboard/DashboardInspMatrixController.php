<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\LibDashboards;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardInspMatrixController extends Controller
{
    private $dashboardConfig;

    public function __construct()
    {
        $cu = CurrentUser::get();
        $this->dashboardConfig = LibDashboards::getAll()[$cu->discipline] ?? null;
        if (!$this->dashboardConfig) {
            abort(404, "Your discipline is not supported for this dashboard - Manage Dashboards.");
        }
    }

    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        dump($this->dashboardConfig);

        return view("dashboards.dashboard-insp-matrix", []);
    }
}
