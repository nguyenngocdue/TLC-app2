<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{

    function getType()
    {
        return "dashboard";
    }


    public function index(Request $request)
    {
        return view("welcome-fortune", []);
    }
}
