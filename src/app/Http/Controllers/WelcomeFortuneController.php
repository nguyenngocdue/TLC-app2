<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {



        return view("welcome-fortune", []);
    }
}
