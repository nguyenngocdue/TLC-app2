<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetEmployeeOvertimeLinesController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        $endpointNameDiginet = "employee-overtime";

        return view("diginet.diginet-transfer-data-employee-overtime-lines", [
            'token' => $token,
            'endpointNameDiginet' => $endpointNameDiginet
        ]);
    }
}
