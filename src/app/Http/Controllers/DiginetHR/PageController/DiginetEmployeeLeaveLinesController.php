<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\DiginetHR\ParentDiginetDataController;
use Illuminate\Http\Request;

class DiginetEmployeeLeaveLinesController extends ParentDiginetDataController
{

    public function index(Request $request)
    {
        $this->endpointNameDiginet = "employee-leave";
        $this->title = 'Employee Leave Lines';
        return parent::index($request);
    }
}
