<?php

namespace App\Http\Controllers\Dashboard\QaqcInsp;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;

class DashboardCouncilMemberController extends DashboardController
{
    public function index(Request $request)
    {
        return view('dashboards.qaqc_insp.dashboard-council-member');
    }
}
