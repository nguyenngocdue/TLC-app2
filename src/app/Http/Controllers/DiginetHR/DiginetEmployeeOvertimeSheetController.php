<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetEmployeeOvertimeSheetController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->endpointNameDiginet = "employee-overtime";
        $this->title = 'Employee Overtime Sheets';
        return parent::index($request);
    }
}
