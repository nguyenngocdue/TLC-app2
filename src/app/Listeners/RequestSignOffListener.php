<?php

namespace App\Listeners;

use App\Events\RequestSignOffEvent;
use App\Mail\MailRequestSignOff;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestSignOffListener
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
     * @param  \App\Events\RequestSignOffEvent  $event
     * @return void
     */
    public function handle(RequestSignOffEvent $event)
    {
        $requesterId = $event->requesterId;
        $requester = User::find($requesterId);

        $receiverId = $event->uids[0];
        $receiver = User::find($receiverId);

        try {
            Mail::to($receiver->email)->send(new MailRequestSignOff([
                'receiverName' => $receiver->name,
                'requesterName' => $requester->name,
                "projectName" => "STW",
                "subProjectName" => "STW1",
                "moduleName" => "STW1-SANDBOX-01",
                "disciplineName" => "Discipline",
                "checksheetName" => "Checksheet",
            ]));
        } catch (\Exception $e) {
            $msg = "Mail Failed to send. Message: " . $e->getMessage();
            Log::error($msg);
            return $msg;
        }

        $id = Signature::create([
            'user_id' => $receiverId,
            'owner_id' => $requesterId,
            'signable_type' => "App\\Models\\Qaqc_insp_chklst_sht",
            'signable_id' => 20033,
            'category' => 159,
        ]);
        Log::info("Signature created: " . $id);
    }
}
