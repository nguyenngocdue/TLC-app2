<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        return view("welcome-fortune", [
            // 'nodeTreeArray' => json_encode(array_values($taskTree))
        ]);
    }
}
