<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\DiginetHR\ParentDiginetDataController;
use Illuminate\Http\Request;

class DiginetEmployeeHoursController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->endpointNameDiginet = "employee-hours";
        $this->title = 'Employee Hours';
        return parent::index($request);
    }
}
