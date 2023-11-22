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
                "message" => "<b>Fortune 123</b> has view checklist of Structure of STW1-11",
                "group_name" =>     'Inspection Check Sheet Observer',
                "sender_id" =>     1,
                "object_type" =>     "App\\Models\\User",
                "object_id" =>     1,
            ],
        ));
        dump($r);
        return "";
    }
}
