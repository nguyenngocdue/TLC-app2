<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    function __construct(
        private WorkingShiftService $wss
    ) {
    }
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);

        $start_at0 = '2023-10-02 01:00:00';
        $end_at0 = '2023-10-24 07:45:25';
        $r = $this->wss->calculateShiftDurationByUser($start_at0, $end_at0, 38);
        dump($r);

        // return view("welcome-fortune", []);
    }
}
