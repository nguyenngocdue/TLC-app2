<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestCronJobController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        if (app()->isProduction()) return "This can not be run in production.";

        if ($request->input('case')) {
            $case = $request->input('case');
            switch ($case) {
                case 'sign_off_remind':
                    event(new \App\Events\SignOffRemindEvent());
                    dump("SignOffRemindEvent emitted.");
                    break;
                case 'transfer_diginet_data':
                    event(new \App\Events\TransferDiginetDataEvent());
                    dump("TransferDiginetDataEvent emitted.");
                    break;
                default:
                    dump($case  . " is not found.");
                    break;
            }
        }

        return view("utils/test-cron-job", [
            // 'request' => $request,
        ]);
    }
}
