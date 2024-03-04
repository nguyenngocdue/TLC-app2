<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
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
