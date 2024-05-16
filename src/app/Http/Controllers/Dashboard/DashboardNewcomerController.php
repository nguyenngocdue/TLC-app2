<?php

namespace App\Http\Controllers\Dashboard;

use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardNewcomerController extends DashboardController
{
    public function index(Request $request)
    {
        $cu = CurrentUser::get();

        return view('dashboards.dashboard-newcomer', ['cu' => $cu]);
    }
}
