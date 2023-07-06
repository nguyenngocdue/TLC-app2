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
        $taskTree = Pj_task::getTasksOfUser(71);

        return view("welcome-fortune", ['nodeTreeArray' => json_encode(array_values($taskTree))]);
    }
}
