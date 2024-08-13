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

class EndOfWeekRemindListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        //
        Log::info("EndOfWeekRemindListener is triggered.");
    }
}
