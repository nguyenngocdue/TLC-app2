<?php

namespace App\Http\Controllers\Utils;

use App\Events\WssDemoChannel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\TestFunction\SendEmail;
use App\Http\Controllers\Utils\TestFunction\TestEmailOnLdapServer;
use App\Jobs\TestLogToFileJob;
use Illuminate\Http\Request;

class TestCronJobController extends Controller
{
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
                case 'sign_off_remind':
                    dump("SignOffRemindEvent emitted.");
                    event(new \App\Events\SignOffRemindEvent());
                    break;
                case 'transfer_diginet_data':
                    dump("TransferDiginetDataEvent emitted.");
                    event(new \App\Events\TransferDiginetDataEvent());
                    break;
                case 'send_test_mail':
                    dump("Test mail sent.");
                    SendEmail::sendTestEmail($request);
                    break;
                case "test_wss":
                    dump("WssDemoChannel emitted.");
                    broadcast(new WssDemoChannel(['name' => 'wss-demo-822553']));
                    break;
                case "test_queue":
                    dump("TestLogToFileJob dispatched.");
                    TestLogToFileJob::dispatch();
                    break;
                case "test_email_on_ldap_server":
                    TestEmailOnLdapServer::Test();
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
