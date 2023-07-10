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
        // $taskTree = Pj_task::getTasksOfUser(71);
        $wp = Workplace::find(2);
        foreach ([2021, 2022, 2023] as $year) {

            $lines = $wp->getTotalWorkingHoursOfYear($year);
            dump($lines);
        }

        $wp = Workplace::find(4);
        foreach ([2021, 2022, 2023] as $year) {

            $lines = $wp->getTotalWorkingHoursOfYear($year);
            dump($lines);
        }
        // return view("welcome-fortune", ['nodeTreeArray' => json_encode(array_values($taskTree))]);
    }
}
