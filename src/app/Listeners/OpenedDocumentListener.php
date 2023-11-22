<?php

namespace App\Listeners;

use App\Events\CreatedDocumentEvent2;
use App\Events\OpenedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\MailCreateNew;
use App\Models\User;
use App\Notifications\SampleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OpenedDocumentListener //implements ShouldQueue
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

    public function handle(OpenedDocumentEvent $event)
    {
        Log::info(json_encode($event));
        $modelPath = $event->modelPath;
        $id = $event->id;
        $type = $event->type;
        $item = $modelPath::find($id);
        // $this->sendMail($item, $type, $id);

        User::find(1)->notify(new SampleNotification(
            [
                "message" => "<b>Fortune 123</b> has view checklist of Structure of STW1-11",
                "group_name" =>     'Inspection Check Sheet Observer',
                "sender_id" =>     1,
                "object_type" => Qaqc_insp_chklst_sht::class,
                "object_id" =>     20009,
                "scroll_to" => 'qaqc_insp_group_id_33_cfc-or-gypsum-boards',
            ],
        ));
    }
}
