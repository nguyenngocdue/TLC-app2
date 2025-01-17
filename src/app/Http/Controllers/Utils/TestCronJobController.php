<?php

namespace App\Http\Controllers\Utils;

use App\Events\CleanUpTrashEvent;
use App\Events\InspectionSignoff\SignOffRemindEvent;
use App\Events\StaffTimesheet\EndOfWeekRemindEvent;
use App\Events\StaffTimesheet\StartOfWeekRemindEvent;
use App\Events\TransferDiginetDataEvent;
use App\Events\WssDemoChannel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\TestFunction\SendEmail;
use App\Http\Controllers\Utils\TestFunction\TestEmailOnLdapServer;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
use App\Http\Services\ProdOrderProgressService;
use App\Http\Services\RoutingLinks\AvgActualHoursForRoutingLinkService;
use App\Jobs\TestLogToFileJob;
use App\Models\Prod_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestCronJobController extends Controller
{
    function __construct(
        private ListFolderService $listFolderService,
        private ListFileService $listFileService,
        private AvgActualHoursForRoutingLinkService $avgActualHoursForRoutingLinkService,
        private ProdOrderProgressService $prodOrderProgressService,
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
                    event(new StartOfWeekRemindEvent());
                    break;
                case 'end_of_week_timesheet_remind':
                    dump("EndOfWeekTimesheetRemindEvent emitted.");
                    event(new EndOfWeekRemindEvent());
                    break;
                case 'sign_off_remind':
                    dump("SignOffRemindEvent emitted.");
                    event(new SignOffRemindEvent());
                    break;
                case 'transfer_diginet_data':
                    dump("TransferDiginetDataEvent emitted.");
                    event(new TransferDiginetDataEvent());
                    break;
                case 'clean_up_trash':
                    dump("CleanUpTrashEvent emitted.");
                    event(new CleanUpTrashEvent());
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
                case "recalculate_avg_actual_hours":
                    $this->avgActualHoursForRoutingLinkService->handle($request);
                    break;
                case "recalculate_matrix_progress":
                    $routingId = $request->input('routing_id');
                    if (!$routingId) {
                        dump("routing_id is required for ProdOrderProgressService.");
                        return;
                    }

                    $allProdOrdersOfARouting = Prod_order::query()
                        ->where('prod_routing_id', $routingId)
                        ->with('getProdSequences')
                        ->get();

                    foreach ($allProdOrdersOfARouting as $prodOrder) {
                        $sequenceId = $prodOrder->getProdSequences->pluck('id')->first();
                        // Log::info("Updating prod_order_progress for sequence_id: " . $sequenceId . " of prod_order_id: " . $prodOrder->id);
                        $this->prodOrderProgressService->update($sequenceId);
                    }
                    dump("Updated " . count($allProdOrdersOfARouting) . " ProdOrder Progress.");
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
