<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\DiginetHR\ParentDiginetDataController;
use Illuminate\Http\Request;

class DiginetEmployeeOvertimeLinesController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->endpointNameDiginet = "employee-overtime";
        $this->title = 'Employee Overtime Lines';
        return parent::index($request);
    }
}
