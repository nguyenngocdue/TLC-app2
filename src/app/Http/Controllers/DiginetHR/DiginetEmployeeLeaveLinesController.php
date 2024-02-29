<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetEmployeeLeaveLinesController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        $endpointNameDiginet = "employee-leave";

        return view("diginet.diginet-transfer-data-employee-leave-lines", [
            'token' => $token,
            'endpointNameDiginet' => $endpointNameDiginet
        ]);
    }
}
