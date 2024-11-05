<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardInspMatrixController extends Controller
{
    public function index(Request $request)
    {
        return view("dashboards.dashboard-insp-matrix", []);
    }
}
