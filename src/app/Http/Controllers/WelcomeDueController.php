<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }



    public function index(Request $request)
    {






        return view("welcome-chart-due", []);
    }
}
