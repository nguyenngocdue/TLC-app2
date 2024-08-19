<?php

namespace App\Listeners\StaffTimesheet;

use App\Mail\STS\MailRemindStaff;
use App\Mail\STS\MailRemindStaffSummaryAdmin;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EndOfWeekRemindListener
{
    private $mailSubject = "Reminder: Weekly Timesheet Submission";
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    private function makeUseList($workplaceIds)
    {
        $users = User::query()
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->whereRaw('unsubscribe_ts_weekly_reminder_for_staff IS NOT TRUE');
        if (app()->isProduction()) {
            $users = $users->whereIn('workplace', $workplaceIds);
        }
        $users = $users->get();

        $workplaces = [];
        foreach ($users as $user) {
            $workplace = $user->current_workplace;
            if (!isset($workplaces[$workplace])) {
                $workplaces[$workplace] = [];
            }
            $workplaces[$workplace][] = $user->name;
        }
        // Log::info($workplaces);
        // Log::info($users->pluck('name', 'id'));
        return $users;
    }

    private function inCronTime()
    {
        $workplaces = Workplace::query()->get();
        $result = [];
        foreach ($workplaces as $workplace) {
            $day = $workplace->remind_timesheet_day;
            $time = substr($workplace->remind_timesheet_time, 0, 5);

            $currentDay = date('N');
            $currentTime = date('H:i');
            // Log::info("Checking $currentDay == $day && $currentTime == $time");
            if ($currentDay == $day && $currentTime == $time) {
                // It's Monday 9:00 AM
                $result[] = $workplace->id;
            }
        }
        // Log::info($result);
        return $result;
    }

    private function sendMailToStaff($users)
    {
        $workplaceNames = [];
        foreach ($users as $user) {
            try {
                $mail = new MailRemindStaff([
                    'user_name' => $user->name,
                    'url' => route("hr_timesheet_officers.index"),
                ]);
                $workplaceName = Workplace::findFromCache($user->current_workplace)->name;
                $workplaceNames[] = $workplaceName;
                $mail->subject = $this->mailSubject . " - ($workplaceName) - " . date('Y');
                Mail::to($user->email)
                    // ->cc([])
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            } catch (\Exception $e) {
                Log::error(get_class() . " " . $e->getMessage() . $e->getFile() . $e->getLine());
            }
        }
        return array_unique($workplaceNames);
    }

    private function sendMailToAdmins($users, $workplaceNames)
    {
        $adminTeam = config('admin_team');

        try {
            $wpNames = join(", ", $workplaceNames);
            $mail = new MailRemindStaffSummaryAdmin(['userLists' => $users]);
            $mail->subject($this->mailSubject . " - ($wpNames) - Summary for Admin Team");

            foreach ($adminTeam as $adminEmail) {
                Mail::to($adminEmail)
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            }
        } catch (\Exception $e) {
            Log::error(get_class() . " " . $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    public function handle(object $event): void
    {
        // Log::info("EndOfWeekRemindListener is triggered.");
        $workplaceIds =  $this->inCronTime();
        $users = $this->makeUseList($workplaceIds);
        $workplaceNames = $this->sendMailToStaff($users);
        if (count($users) > 0) {
            $this->sendMailToAdmins($users, $workplaceNames);
        }
    }
}
