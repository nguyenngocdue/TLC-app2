<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Listeners\UpdatedSubProjectListener;
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
        Timer::getTimeElapseFromLastAccess();
        UpdatedSubProjectListener::reloadExternalInspectors(112);

        echo Timer::getTimeElapseFromLastAccess();;
        return "";
    }
}
