<?php

namespace App\Http\Controllers;

use App\Models\User_team_ot;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        return view("welcome-fortune", []);
    }
}
