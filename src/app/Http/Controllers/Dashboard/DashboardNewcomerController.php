<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

class DashboardNewcomerController extends DashboardController
{
    public function index(Request $request)
    {
        $userSettings = $this->getUserSettings();

        return view('dashboards.dashboard-newcomer');
    }
}
