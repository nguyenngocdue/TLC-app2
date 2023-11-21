<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\User;
use App\Notifications\SampleNotification;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
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
        $r = User::find(1)->notify(new SampleNotification(123));
        dump($r);
        return "";
    }
}
