<?php

namespace App\Http\Controllers\Utils;

use App\Events\WssDemoChannel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\TestFunction\SendEmail;
use App\Http\Controllers\Utils\TestFunction\TestEmailOnLdapServer;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
use App\Jobs\TestLogToFileJob;
use Illuminate\Http\Request;

class TestCronJobController extends Controller
{
    function __construct(
        private ListFolderService $listFolderService,
        private ListFileService $listFileService,
    ) {}

    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        // if (app()->isProduction()) return "This can not be run in production.";

        if ($request->input('case')) {
            $case = $request->input('case');
            switch ($case) {
                case 'start_of_week_timesheet_remind':
                    dump("StartOfWeekTimesheetRemindEvent emitted.");
                    event(new \App\Events\StaffTimesheet\StartOfWeekRemindEvent());
                    break;
                case 'sign_off_remind':
                    dump("SignOffRemindEvent emitted.");
                    event(new \App\Events\InspectionSignoff\SignOffRemindEvent());
                    break;
                case 'transfer_diginet_data':
                    dump("TransferDiginetDataEvent emitted.");
                    event(new \App\Events\TransferDiginetDataEvent());
                    break;
                case 'clean_up_trash':
                    dump("CleanUpTrashEvent emitted.");
                    event(new \App\Events\CleanUpTrashEvent());
                    break;
                case 'send_test_mail':
                    dump("Test mail sent.");
                    SendEmail::sendTestEmail($request);
                    break;
                case "test_wss":
                    dump("WssDemoChannel emitted.");
                    broadcast(new WssDemoChannel(['name' => "wss-demo-822553 from " . env("APP_NAME") . " " . env("APP_ENV"), "payload" => "Tested successfully."]));
                    break;
                case "test_queue":
                    dump("TestLogToFileJob dispatched.");
                    TestLogToFileJob::dispatch();
                    break;
                case "test_email_on_ldap_server":
                    TestEmailOnLdapServer::Test();
                    break;
                case "refresh_attachment_orphan":
                    $this->listFolderService->handle();
                    $this->listFileService->handle();
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
