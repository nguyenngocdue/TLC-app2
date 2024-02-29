<?php

namespace App\Http\Controllers;

use App\Http\Services\DiginetGetEmployeeHoursService;
use App\Utils\Support\APIDiginet;
use Illuminate\Http\Request;


class TransferDiginetDataForApi
{
    public function store(Request $request)
    {
        $params = $request->input();
        $data = APIDiginet::getDatasourceFromAPI("employee-hours", $params)['data'];
        $insService = new DiginetGetEmployeeHoursService();
        $insService->createAndUpdateData($data, $params, 'employee_hours');
    }
}
