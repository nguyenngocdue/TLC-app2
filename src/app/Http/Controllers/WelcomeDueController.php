<?php

namespace App\Http\Controllers;

use App\Utils\Support\AttachmentName;
use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {



        return view("welcome-due", []);
    }
}
