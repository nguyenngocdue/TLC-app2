<?php

namespace App\Http\Controllers\DiginetHR\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DiginetService;

class TransferDiginetDataEmployeeLeavesForApi extends Controller
{
    public function store(Request $request)
    {
        $params = $request->input();
        $keys = ['employee-leave-sheets', 'employee-leave-lines'];
        return (new DiginetService())->getDataAndStore($keys, $params);
    }
}
