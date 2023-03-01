<?php

namespace App\Listeners;

use App\Events\CreateNewDocumentEvent;
use App\Models\User;
use App\Notifications\CreateNewNotification;
use App\Utils\Constant;
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
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case Constant::OWNER_ID:
                    $createNotification[$key] = $value;
                    break;
                default:
                    break;
            }
        }
        $this->checkAndSendNotification($createNotification, $currentValue, 'created');
    }
    private function checkAndSendNotification($array, $currentValue, $type)
    {
        $userSchema = User::find(array_shift($array));
        Notification::send($userSchema, new CreateNewNotification(['type' => $type, 'currentValue' => $currentValue]));
    }
}
