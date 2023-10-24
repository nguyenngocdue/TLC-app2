<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitApi\TraitKanbanWorkingShift;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    use TraitKanbanWorkingShift;
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);
        // return "AAA";

        // $weekendDays = [0, 6]; // 0 for Sunday, 6 for Saturday
        // $publicHolidays = [
        //     '2023-10-25', // Example public holiday date
        //     // Add other public holidays as needed
        // ];

        $start_at0 = '2023-10-24 04:30:00';
        $end_at0 = '2023-10-24 06:30:00';
        // // $start_at0 = '2023-10-27 00:00:00';
        // // $end_at0 = '2023-10-29 23:59:59';
        // // Define your shifts (adjust as per your specific shifts)
        // $shifts = [
        //     ['start' => '01:00:00', 'end' => '05:00:00'],
        //     ['start' => '06:00:00', 'end' => '10:30:00'],
        //     // Add other shifts as needed
        // ];
        // $r = $this->calculateShiftDuration($start_at0, $end_at0, $shifts, $weekendDays, $publicHolidays);

        $r = $this->calculateShiftDurationByUser($start_at0, $end_at0, 38);
        dump($r);

        // return view("welcome-fortune", []);
    }
}
