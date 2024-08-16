<?php

namespace App\Listeners\StaffTimesheet;

use App\Mail\STS\MailRemindManager;
use App\Mail\STS\MailRemindStaff;
use App\Models\Hr_timesheet_officer;
use App\Models\User;
use App\Models\User_discipline;
use App\Models\Workplace;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use LDAP\Result;

class EndOfWeekRemindListener
{
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

    private function checkTimesheet($users)
    {
        foreach ($users as $user) {
            try {

                $mail = new MailRemindStaff([
                    'user_name' => $user->name,
                    'url' => route("hr_timesheet_officers.index"),
                ]);
                $workplaceName = Workplace::findFromCache($user->current_workplace)->name;
                $mail->subject = "Weekly Timesheet Reminder - ($workplaceName) - " . date('Y');
                Mail::to($user->email)
                    // ->cc([])
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            } catch (\Exception $e) {
                Log::error(get_class() . " " . $e->getMessage() . $e->getFile() . $e->getLine());
            }
        }
    }

    public function handle(object $event): void
    {
        // Log::info("EndOfWeekRemindListener is triggered.");
        $workplaceIds =  $this->inCronTime();
        $users = $this->makeUseList($workplaceIds);
        $this->checkTimesheet($users);
    }
}
