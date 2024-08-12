<?php

namespace App\Listeners;

use App\Mail\STS\MailRemindManager;
use App\Models\User;
use App\Models\User_discipline;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StartOfWeekTimesheetRemindListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        Log::info("StartOfWeekTimesheetRemindListener is triggered.");
        $users = User::query()
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->get();
        $count = 1;

        $disciplines = [];
        foreach ($users as $user) {
            // $user->notify(new StartOfWeekTimesheetRemindNotification());
            $discipline_id = $user->discipline;
            $discipline = User_discipline::findFromCache($discipline_id);
            $manager_id = $discipline->def_assignee;
            $manager = User::findFromCache($manager_id);

            $proxy_id = $manager->ts_proxy_approver_id;
            $proxy = User::findFromCache($proxy_id ?? $manager_id);

            if (!isset($disciplines[$manager_id])) {
                $disciplines[$manager_id] = [
                    "def_assignee" => $manager->name,
                    "proxy_name" => $proxy->name,
                    "email" => $proxy->email,
                    "staff_list" => [],
                ];
            }

            $disciplines[$manager_id]["staff_list"][] = $user->name;
            $debugMessage = "User #" . str_pad($user->id, 4, '0', STR_PAD_LEFT) . " - " . $user->name . " ($discipline) notified.";
            // Log::info($debugMessage);
        }
        Log::info($disciplines);

        $users = User::query()->where('id', 1)->get();
        foreach ($users as $user) {
            $mail = new MailRemindManager(
                [
                    "user" => $user,
                    "url" => route("hr_timesheet_officers.index"),
                    "items" => [
                        'x' => [
                            'a' => 'Start of the week timesheet reminder',
                            'b' => 'Please fill in your timesheet for the week.',
                            'c' => 'Thank you.',
                        ],
                        'y' => [
                            'a' => 'Start of the week timesheet reminder',
                            'b' => 'Please fill in your timesheet for the week.',
                            'c' => 'Thank you.',
                        ],
                    ],
                ]
            );

            Mail::to($user->email)->send($mail);
        }
    }
}
