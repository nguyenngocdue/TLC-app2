<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DiginetBusinessTripSheetsController extends ParentDiginetDataController
{
    public function index(Request $request)
    {
        $this->token = CurrentUser::getTokenForApi();
        $this->endpointNameDiginet = "business-trip";
        $this->title = 'Business Trip Sheets';
        return parent::index($request);
    }
}
