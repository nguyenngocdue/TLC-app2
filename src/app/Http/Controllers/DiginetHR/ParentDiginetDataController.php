<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

abstract class ParentDiginetDataController extends Controller
{
    protected $endpointNameDiginet;
    protected $topTitle = 'Update Diginet Data';
    protected $title;

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        return view("diginet.diginet-transfer-data", [
            'token' => $token,
            'endpointNameDiginet' => $this->endpointNameDiginet,
            'topTitle' => $this->topTitle,
            'title' => $this->title
        ]);
    }
}
