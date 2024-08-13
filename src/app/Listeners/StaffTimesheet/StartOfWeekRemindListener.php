<?php

namespace App\Listeners\StaffTimesheet;

use App\Mail\STS\MailRemindManager;
use App\Models\Hr_timesheet_officer;
use App\Models\User;
use App\Models\User_discipline;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StartOfWeekRemindListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    private function makeUserList()
    {
        $users = User::query()
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->get();
        $count = 1;
        $disciplines = [];
        $userIdsToCheck = [];
        foreach ($users as $user) {
            $discipline_id = $user->discipline;
            $discipline = User_discipline::findFromCache($discipline_id);
            $manager_id = $discipline->def_assignee;
            $manager = User::findFromCache($manager_id);

            $proxy_id = $manager->ts_proxy_approver_id;
            $proxy = User::findFromCache($proxy_id ?? $manager_id);

            if (!isset($disciplines[$manager_id])) {
                $disciplines[$manager_id] = [
                    // 'user' => $proxy,
                    'user_name' => $proxy->name,
                    'user_email' => $proxy->email,
                    "def_assignee" => $manager->name,
                    // "proxy_name" => $proxy->name,
                    // "email" => $proxy->email,
                    "url" => route('hr_timesheet_officers.index'),
                    "staff_list" => [],
                    "staff_emails" => [],
                ];
            }

            $userIdsToCheck[] = $user->id;
            $disciplines[$manager_id]["staff_list"][$user->id] = [
                'staff_name' => $user->name,
                // 'staff_email' => $user->email,
                // 'links' => [], //"Not yet submitted",
                'linkStr' => "Not yet submitted",
            ];
            $disciplines[$manager_id]["staff_emails"][] = $user->email;
            $debugMessage = ($count++) . " User #" . str_pad($user->id, 4, '0', STR_PAD_LEFT) . " - " . $user->name . " ($discipline) notified.";
            // Log::info($debugMessage);
        }

        $timesheets = $this->getTimesheets($userIdsToCheck);

        foreach ($disciplines as $manager_id => $manager) {
            $staffList = $manager['staff_list'];
            $statuses = [];
            foreach ($staffList as $staff_id => $staff) {
                $links = [];
                if (isset($timesheets[$staff_id])) {
                    $tss = $timesheets[$staff_id];
                    foreach ($tss as $ts) {
                        $href = route('hr_timesheet_officers.edit', $ts->id);
                        $statusTitle = Str::headline($ts->status);
                        $idTitle = Str::makeId($ts->id);
                        $links[] = "<a href='$href' style=''>$statusTitle ($idTitle) </a>";
                        // $links[] = [
                        //     'status' => $ts->status,
                        //     'href' => $href,
                        // ];
                    }
                    $staffList[$staff_id]['linkStr'] = join("<br/>", $links);
                }
            }
            // $staffList[$staff_id]['links'] = $links;
            // $staffList[$staff_id]['statuses'] = $statuses;

            $disciplines[$manager_id]['staff_list'] = $staffList;
        }

        // Log::info($userIdsToCheck);
        // Log::info($disciplines);
        return $disciplines;
    }

    private function getTimesheets($user_ids)
    {
        $mondayLastWeek = date('Y-m-d', strtotime('monday last week'));
        $satLastWeek = date('Y-m-d', strtotime('saturday last week'));
        $timesheets = Hr_timesheet_officer::query()
            ->whereIn('owner_id', $user_ids)
            ->where('week', '>=', $mondayLastWeek)
            ->where('week', '<=', $satLastWeek)
            ->get();

        $result = [];
        foreach ($timesheets as $ts) {
            $result[$ts->owner_id][] = $ts;
        }

        // Log::info($user_ids);
        // Log::info($timesheet);
        return $result;
    }

    public function handle(object $event): void
    {
        //
        Log::info("StartOfWeekRemindListener is triggered.");

        $userLists = $this->makeUserList();

        // Log::info($userLists);
        // $managers = User::query()->where('id', 1)->get();
        foreach ($userLists as $list) {
            $mail = new MailRemindManager($list);
            $user_name = $list['user_name'];
            $user_email = $list['user_email'];
            $mail->subject("Reminder: Start of the week timesheet submission - " . $user_name);

            // Log::info($user->email);
            // Log::info($list['staff_emails']);

            try {
                Mail::to($user_email)
                    ->cc($list['staff_emails'])
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            } catch (\Exception $e) {
                Log::error(get_class() . $e->getMessage() . $e->getFile() . $e->getLine());
            }
        }
    }
}
