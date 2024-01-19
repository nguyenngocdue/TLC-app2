<?php

namespace App\Listeners;

use App\Events\SignOffRemindEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\TraitSignOffListener;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SignOffRemindListener //implements ShouldQueue
{
    use TraitSignOffListener;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(SignOffRemindEvent $event)
    {
        Log::info("Xuat sac.");
        // Log::info($event->task->command);
        // Log::info($event->runtime);
    }
}
