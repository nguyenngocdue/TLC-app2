<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use App\Http\Services\DiginetService;
use Illuminate\Http\Request;

class DiginetUpdateAllTablesController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        $endpointNameDiginet = "all-tables";
        return view("diginet.transfer-diginet-data-update-all-table", [
            'token' => $token,
            'endpointNameDiginet' => $endpointNameDiginet
        ]);
    }
}
