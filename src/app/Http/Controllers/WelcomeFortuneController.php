<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\Qaqc_insp_chklst_sht;
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
                "object_type" => Qaqc_insp_chklst_sht::class,
                "object_id" =>     20009,
                "scroll_to" => 'qaqc_insp_group_id_33_cfc-or-gypsum-boards',
            ],
        ));
        dump($r);
        return "";
    }
}
