<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\DiginetHR\ParentDiginetDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DiginetEmployeeHoursController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->endpointNameDiginet = "employee-hours";
        $this->title = 'Employee Hours';
        return parent::index($request);
    }
}
