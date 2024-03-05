<?php

namespace App\Http\Controllers\DiginetHR\PageController;

use App\Http\Controllers\DiginetHR\ParentDiginetDataController;
use Illuminate\Http\Request;

class DiginetBusinessTripLinesController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->endpointNameDiginet = "business-trip";
        $this->title = 'Business Trip Lines';
        return parent::index($request);
    }
}
