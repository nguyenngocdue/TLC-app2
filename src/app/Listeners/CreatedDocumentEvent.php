<?php

namespace App\Listeners;

use App\Events\CreatedDocumentEvent;
use App\Models\User;
use App\Notifications\CreateNewNotification;
use App\Utils\Support\Json\BallInCourts;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class CreatedDocumentListener implements ShouldQueue
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

    public function handle(CreatedDocumentEvent $event)
    {
        $createNotification = [];
        $currentValue = $event->{'currentValue'};
        $modelPath = $event->{'modelPath'};
        $type = $event->{'type'};
        if (!$currentValue['status']) {
            Toastr::warning('Send Notifications Warning!', 'Please check columns status or DefaultValues.json doest not exist');
            return;
        }
        if (!$currentValue['owner_id']) {
            Toastr::warning('Send Notifications Warning!', 'Please check columns owner_id or DefaultValues.json doest not exist');
            return;
        }
        $ballInCourts = BallInCourts::getAllOf($type);
        $keySendMailCreate = 'owner_id';
        if (isset($ballInCourts['new'])) {
            $keySendMailCreate = $ballInCourts['new']['ball-in-court-assignee'] ?? 'owner_id';
        }
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case $keySendMailCreate:
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
        $user = User::find(array_shift($array));
        Notification::send($user, new CreateNewNotification(['type' => $type, 'currentValue' => $currentValue]));
    }
}
