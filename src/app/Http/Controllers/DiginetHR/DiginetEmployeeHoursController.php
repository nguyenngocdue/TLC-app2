<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetEmployeeHoursController extends Controller
{

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        return view("diginet.diginet-transfer-data-employee-hours", [
            'token' => $token,
        ]);
    }
}
