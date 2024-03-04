<?php

namespace App\Http\Controllers\DiginetHR;

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
        $params = $request->input();
        $token = CurrentUser::getTokenForApi();
        $endpointNameDiginet = "all-tables";
        dump($params);
        if ($params) {
            $params = key($params);
            $params = json_decode($params, true);
            dump($params);

            $ins = new DiginetService();

            // request many APIs
            $ins->getDataAndStore(['employee-hours'], $params);
            $ins->getDataAndStore(['business-trip-sheets', 'business-trip-lines'], $params);
            $ins->getDataAndStore(['employee-leave-sheets', 'employee-leave-lines'], $params);
            $ins->getDataAndStore(['employee-overtime-sheets', 'employee-overtime-lines'], $params);
        }
        return view("diginet.diginet-transfer-data-update-all-table", [
            'token' => $token,
            'endpointNameDiginet' => $endpointNameDiginet
        ]);
    }
}
