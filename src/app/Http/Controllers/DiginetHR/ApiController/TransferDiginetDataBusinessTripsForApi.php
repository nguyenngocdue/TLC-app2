<?php

namespace App\Http\Controllers\DiginetHR\ApiController;

use App\Http\Controllers\Controller;
use App\Http\Services\DiginetService;
use Illuminate\Http\Request;

class TransferDiginetDataBusinessTripsForApi extends Controller
{
    public function store(Request $request)
    {
        $params = $request->input();
        $keys = ['business-trip-sheets', 'business-trip-lines'];
        $ins = new DiginetService();
        return $ins->getDataAndStore($keys, $params);
    }
}
