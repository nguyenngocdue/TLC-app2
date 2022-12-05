<?php

namespace App\Listeners;

use App\Events\SendEmailItemCreated;
use App\Mail\UserMail;
use App\Models\Department;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendEmailItemCreated  $event
     * @return void
     */
    public function handle(SendEmailItemCreated $dataEvent)
    {
        $data = Department::find($dataEvent->dataEvent['id'])->toArray();
        // Mail::send('mails.eventMail', $data, function ($message) use ($data) {
        //     $message->to('duengocnguyen@gmail.com');
        //     $message->subject('Event TestCase');
        // });
        Mail::to('duengocnguyen@gmail.com')->send(new UserMail($dataEvent));
    }
}
