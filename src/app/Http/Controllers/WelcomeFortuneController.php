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
        // $lines = $wp->getTotalWorkingHoursOfMonth('2022-11');
        foreach ([
            "2021-01", "2021-02", "2021-03", "2021-04", "2021-05", "2021-06",
            "2021-07", "2021-08", "2021-09", "2021-10", "2021-11", "2021-12",
            "2022-01", "2022-02", "2022-03", "2022-04", "2022-05", "2022-06",
            "2022-07", "2022-08", "2022-09", "2022-10", "2022-11", "2022-12",
            "2023-01", "2023-02", "2023-03", "2023-04", "2023-05", "2023-06",
            "2023-07", "2023-08", "2023-09", "2023-10", "2023-11", "2023-12",
        ] as $month) {
            $hours = $wp->getTotalWorkingHoursOfMonth($month);
            echo ($month . " - " . number_format($hours, 0) . "<br/>");
        }
        // $index = 0;
        // foreach ($lines as $uid => $x) {
        //     echo ($uid . " - " . $x['total'] . "<br/>");
        //     // if ($index++ > 10) break;
        // }
        // return view("welcome-fortune", ['nodeTreeArray' => json_encode(array_values($taskTree))]);
    }
}
