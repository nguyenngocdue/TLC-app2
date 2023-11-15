<?php

namespace App\Listeners;

use App\Events\RequestSignOffEvent;
use App\Events\WssToastrMessageChannel;
use App\Mail\MailRequestSignOff;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $data = $event->data;
        $requesterId = $data['requesterId'];
        $requester = User::find($requesterId);

        $receiverId = $data['uids'][0];
        $receiver = User::find($receiverId);

        $category_id = FieldSeeder::getIdFromFieldName($data['category']);

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
            $msg = "Mail to <b>{$receiver->email}</b> Failed.<br/>" . $e->getMessage();
            // Log::error($msg);
            broadcast(new WssToastrMessageChannel([
                'type' => 'error',
                'message' => $msg,
            ]));
            return $msg;
        }

        Signature::create([
            'user_id' => $receiverId,
            'owner_id' => $requesterId,
            'signable_type' => Str::modelPathFrom($data['tableName']),
            'signable_id' => $data['signableId'],
            'category' => $category_id,
        ]);
        broadcast(new WssToastrMessageChannel([
            'type' => 'success',
            'message' => "Email to <b>{$receiver->email}</b> sent successfully.",
        ]));
    }
}
