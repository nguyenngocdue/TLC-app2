<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DiginetService;

class TransferDiginetDataEmployeeOvertimesForApi extends Controller
{
    public function store(Request $request)
    {
        $params = $request->input();
        $keys = ['employee-overtime-sheets', 'employee-overtime-lines'];
        $ins = new DiginetService();
        return $ins->getDataAndStore($keys, $params);
    }
}
