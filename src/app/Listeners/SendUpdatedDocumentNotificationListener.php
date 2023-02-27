<?php

namespace App\Listeners;

use App\Events\UpdatedDocumentEvent;
use App\Models\User;
use App\Notifications\UpdatedNotification;
use App\Utils\Constant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendUpdatedDocumentNotificationListener
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
     * @param  \App\Events\UpdatedDocumentEvent  $event
     * @return void
     */
    public function handle(UpdatedDocumentEvent $event)
    {
        $assigneeNotification = [];
        $monitorNotification = [];
        $createNotification = [];
        $previousValue = $event->{'previousValue'};
        $currentValue = $event->{'currentValue'};
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case in_array($key, Constant::LIST_ASSIGNEE):
                    $assigneePrevious = $previousValue[$key];
                    $value == $assigneePrevious ? $assigneeNotification[$key] = null : $assigneeNotification[$key] = $value;
                    break;
                case Constant::GET_MONITOR:
                    $monitorPrevious = $previousValue[$key];
                    $monitorNotification[$key] = array_diff($value, $monitorPrevious);
                    break;
                case Constant::OWNER_ID:
                    $createPrevious = $previousValue[$key];
                    $value == $createPrevious ? $createNotification[$key] = null : $createNotification[$key] = $value;
                    break;
                default:
                    break;
            }
        }
        $this->checkAndSendNotification($assigneeNotification, $currentValue, 'assignee');
        $this->checkAndSendNotification($monitorNotification, $currentValue, 'monitor');
    }
    private function checkAndSendNotification($array, $currentValue, $type)
    {
        foreach ($array as $value) {
            if ($value) {
                if (is_array($value)) {
                    foreach ($value as $id) {
                        $userSchema = User::find($id);
                        Notification::send($userSchema, new UpdatedNotification(['type' => $type, 'currentValue' => $currentValue]));
                    }
                } else {
                    $userSchema = User::find($value);
                    Notification::send($userSchema, new UpdatedNotification(['type' => $type, 'currentValue' => $currentValue]));
                }
            }
        }
    }
}
