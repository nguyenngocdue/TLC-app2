<?php

namespace App\Http\Controllers;

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
        $view = "dashboard" . CurrentUser::getViewSuffix();
        return view('dashboards.' . $view,);
    }
}
