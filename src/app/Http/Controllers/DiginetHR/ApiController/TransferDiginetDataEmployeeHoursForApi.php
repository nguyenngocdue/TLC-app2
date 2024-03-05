<?php

namespace App\Http\Controllers\DiginetHR\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DiginetService;

class TransferDiginetDataEmployeeHoursForApi extends Controller
{
    public function store(Request $request)
    {
        $params = $request->input();
        $keys = ['employee-hours'];
        $fn = new DiginetService();
        return $fn->getDataAndStore($keys, $params);
    }
}
