<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        dd(123);
        return view("welcome-due", [
            // 'nodeTreeArray' => json_encode(array_values($taskTree))
        ]);
    }
}
