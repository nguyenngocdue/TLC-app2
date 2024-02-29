<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetBusinessTripLinesController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        $endpointNameDiginet = "business-trip";

        return view("diginet.diginet-transfer-data-business-trip-lines", [
            'token' => $token,
            'endpointNameDiginet' => $endpointNameDiginet
        ]);
    }
}
