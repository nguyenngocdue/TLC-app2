<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferDataDiginetToApp extends Controller
{

    function getType()
    {
        return "dashboard";
    }


    public function index(Request $request)
    {
        return view('diginet.transfer-data-diginet-to-app');
    }
}
