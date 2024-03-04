<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
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
