<?php

namespace App\Listeners;

use App\Events\OpenedDocumentEvent;
use App\Models\User;
use App\Notifications\SampleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
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
        // Log::info(json_encode($event));
        $modelPath = $event->modelPath;
        $id = $event->id;
        $type = $event->type;
        $cuid = $event->cuid;
        $sender = User::find($cuid);
        $senderName = $sender->name;
        $item = $modelPath::find($id);
        $itemName = $item?->name;

        // switch ($type) {
        //     case 'qaqc_insp_chklst_sht':
        //         if ($sender->isExternalInspector()) {
        //             $book = $item->getChklst;
        //             $prodOrderName = $book->getProdOrder->name;
        //             $subProjectName = $book->getSubProject->name;
        //             User::find(1)->notify(new SampleNotification([
        //                 "message" => "<b>$senderName</b> opened $itemName of $prodOrderName ($subProjectName).",
        //                 "group_name" => 'ICS Observer',
        //                 "sender_id" => $cuid,
        //                 "object_type" => $modelPath,
        //                 "object_id" => $id,
        //                 "scroll_to" => '', //'qaqc_insp_group_id_33_cfc-or-gypsum-boards',
        //             ]));
        //         }
        //         break;
        // }
    }
}
