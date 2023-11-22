<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\User;
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
        $r = User::find(1)->notify(new SampleNotification(
            
            [
                "content" => "<b>Fortune</b> has view checklist of Structure of STW1-11",
            ],
            'example_group',
            1,
            "App\\Models\\User",
            1,
        ));
        dump($r);
        return "";
    }
}
