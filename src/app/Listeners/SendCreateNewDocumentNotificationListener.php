<?php

namespace App\Listeners;

use App\Events\CreateNewDocumentEvent;
use App\Models\Logger;
use App\Models\User;
use App\Notifications\CreateNewNotification;
use App\Utils\Constant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendCreateNewDocumentNotificationListener implements ShouldQueue
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
     * @param  \App\Events\CreateNewDocumentEvent  $event
     * @return void
     */
    public function handle(CreateNewDocumentEvent $event)
    {
        $createNotification = [];
        $currentValue = $event->{'currentValue'};
        $classType = $event->{'classType'};
        // dd($currentValue['owner_id']);
        if (!$currentValue['status']) {
            Toastr::warning('Send Notifications Warning!', 'Please check columns status or DefaultValues.json doest not exist');
            return;
        }
        if (!$currentValue['owner_id']) {
            Toastr::warning('Send Notifications Warning!', 'Please check columns owner_id or DefaultValues.json doest not exist');
            return;
        }
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case Constant::OWNER_ID:
                    $createNotification[$key] = $value;
                    break;
                default:
                    break;
            }
        }
        $this->checkAndSendNotification($createNotification, $currentValue, 'created', $classType);
    }
    private function checkAndSendNotification($array, $currentValue, $type, $classType)
    {
        $user = User::find(array_shift($array));
        $this->insertLogger($currentValue, $classType);
        Notification::send($user, new CreateNewNotification(['type' => $type, 'currentValue' => $currentValue]));
    }
    private function insertLogger($currentValue, $classType)
    {
        Logger::create([
            'loggable_type' => $classType,
            'loggable_id' => $currentValue['id'],
            'type' => 'created_entity',
            'key' => 'create',
            'user_id' => $currentValue['owner_id'],
            'owner_id' => $currentValue['owner_id'],
            'created_at' => $currentValue['created_at'],
        ]);
    }
}
