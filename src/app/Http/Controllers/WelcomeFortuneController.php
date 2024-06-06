<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
