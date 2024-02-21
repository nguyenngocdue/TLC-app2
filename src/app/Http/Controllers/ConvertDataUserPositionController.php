<?php

namespace App\Http\Controllers;

use App\Utils\ConvertDataUserPosition;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class ConvertDataUserPositionController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        if(CurrentUser::isAdmin()){
            ConvertDataUserPosition::handle();
        }
    }
}
