<?php

namespace App\Http\Controllers\Dashboard\QaqcInsp;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;

class DashboardShippingAgentController extends DashboardController
{
    public function index(Request $request)
    {
        // echo "DashboardShippingAgentController";
        return view('dashboards.qaqc_insp.dashboard-shipping-agent');
    }
}
