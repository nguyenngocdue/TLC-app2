<?php

namespace App\Listeners;

use App\Events\CreateNewDocumentEvent;
use App\Models\User;
use App\Notifications\CreateNewNotification;
use App\Utils\Constant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendCreateNewDocumentNotificationListener
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
        $assigneeNotification = [];
        $monitorNotification = [];
        $createNotification = [];
        $currentValue = $event->{'currentValue'};
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case in_array($key, Constant::LIST_ASSIGNEE):
                    $assigneeNotification[$key] = $value;
                    break;
                case Constant::GET_MONITOR:
                    $monitorNotification[$key] = $value;
                    break;
                case Constant::OWNER_ID:
                    $createNotification[$key] = $value;
                    break;
                default:
                    break;
            }
        }
        $this->checkAndSendNotification($assigneeNotification, $currentValue, 'assignee');
        $this->checkAndSendNotification($monitorNotification, $currentValue, 'monitor');
        $this->checkAndSendNotification($createNotification, $currentValue, 'created');
    }
    private function checkAndSendNotification($array, $currentValue, $type)
    {
        foreach ($array as $item) {
            if (is_array($item)) {
                foreach ($item as  $value) {
                    $userSchema = User::find($value);
                    Notification::send($userSchema, new CreateNewNotification(['type' => $type, 'currentValue' => $currentValue]));
                }
            } else {
                $userSchema = User::find($item);
                Notification::send($userSchema, new CreateNewNotification(['type' => $type, 'currentValue' => $currentValue]));
            }
        }
    }
}
