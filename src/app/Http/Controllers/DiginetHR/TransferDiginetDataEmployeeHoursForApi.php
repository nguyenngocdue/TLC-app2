<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Services\DiginetGetEmployeeHoursService;
use App\Utils\Support\APIDiginet;
use Illuminate\Http\Request;


class TransferDiginetDataEmployeeHoursForApi
{
    public function store(Request $request)
    {
        $endpointName = "employee-hours";
        $params = $request->input();
        $insService = new DiginetGetEmployeeHoursService();
        $insService->createAndUpdateData($params, $endpointName);
    }
}
