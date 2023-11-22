<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\Workplace;
use App\Notifications\SampleNotification;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

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

        $wps = Workplace::all();
        foreach ($wps as $wp) {
            dump($wp->getTotalWorkingHoursOfYear(2023));
        }
        // $r = User::find(1)->notify(new SampleNotification([
        //     'object_type' => 'App\\Models\\User',
        //     'object_id' => 1,
        //     'sender_id' => 444,
        //     'content' => "<b>Fortune</b> has view checklist of Structure of STW1-11",
        // ]));
        // dump($r);
        return "";
    }
}
