<?php

namespace App\Http\Controllers;

use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);
        return view("welcome-fortune", []);
    }
}
