<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\APIDiginet;
use App\Utils\Support\CurrentUser;
use DiginetGetEmployeeHoursService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiginetEmployeeHourController extends Controller
{

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        $X_AccessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.9C4Wv1O56qRbC-fOvFbqZWDjhdvKz6D4kc-e9nZ04Co';

        return view("diginet.transfer-data-diginet", [
            'token' => $token,
            'xAccessToken' => $X_AccessToken

        ]);
    }
}
