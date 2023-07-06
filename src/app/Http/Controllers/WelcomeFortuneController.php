<?php

namespace App\Http\Controllers;

use App\Models\Pj_task;
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

        $tasks = Pj_task::getTasksOfUser(71);
        dump($tasks);

        return view("welcome-fortune", []);
    }
}
