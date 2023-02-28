<?php

namespace App\Listeners;

use App\Events\UpdatedDocumentEvent;
use App\Models\User;
use App\Notifications\UpdatedNotification;
use App\Utils\Constant;
use App\Utils\Support\Json\BallInCourts;
use App\Utils\Support\JsonControls;
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
        $changStatusNotification = [];
        $previousValue = $event->{'previousValue'};
        $currentValue = $event->{'currentValue'};
        $listAssignees = JsonControls::getAssignees();
        $listMonitors = JsonControls::getMonitors();
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case in_array($key, $listAssignees):
                    $assigneePrevious = $previousValue[$key];
                    $value == $assigneePrevious ? $assigneeNotification[$key] = null : $assigneeNotification[$key] = $value;
                    [$outBallInCourtAssignee, $newBallInCourtAssignee] = $this->isChangeBallInCourt($previousValue, $currentValue, $listAssignees);
                    if ($newBallInCourtAssignee) {
                        if ($newBallInCourtAssignee == 'creator') {
                            $changStatusNotification[$outBallInCourtAssignee] = $currentValue['owner_id'];
                        } else {
                            $changStatusNotification[$outBallInCourtAssignee] = $value;
                        }
                    }
                    break;
                case in_array($key, $listMonitors):
                    $monitorPrevious = $previousValue[$key];
                    $monitorNotification[$key] = array_diff($value, $monitorPrevious);
                    [$outBallInCourtMonitors, $newBallInCourtMonitors] = $this->isChangeBallInCourt($previousValue, $currentValue, $listMonitors, 'ball-in-court-monitors');
                    if ($newBallInCourtMonitors) {
                        $changStatusNotification[$outBallInCourtMonitors] = $value;
                    }
                    break;
                default:
                    break;
            }
        }
        // $this->checkAndSendNotification($assigneeNotification, $previousValue, $currentValue, 'assignee');
        $this->checkAndSendNotification($monitorNotification, $previousValue, $currentValue, 'monitor');
        $this->checkAndSendNotification($changStatusNotification, $previousValue, $currentValue, 'change_status');
    }
    private function isChangeBallInCourt($previousValue, $currentValue, $listAssignees, $typeBallInCourt = 'ball-in-court-assignee')
    {
        $previousStatus = $previousValue['status'];
        $currentStatus = $currentValue['status'];
        $type = $currentValue['entity_type'];
        $ballInCourts = array_map(function ($item) use ($listAssignees, $typeBallInCourt) {
            if (!$item[$typeBallInCourt]) {
                $item[$typeBallInCourt] = $listAssignees[0];
            }
            return $item;
        }, BallInCourts::getAllOf($type));
        $outBallInCourt = $ballInCourts[$previousStatus];
        $newBallInCourt = $ballInCourts[$currentStatus];
        if ($newBallInCourt[$typeBallInCourt] == $outBallInCourt[$typeBallInCourt]) {
            return [false, false];
        }
        return [$outBallInCourt[$typeBallInCourt], $newBallInCourt[$typeBallInCourt]];
    }
    private function checkAndSendNotification($array, $previousValue, $currentValue, $type)
    {
        $array = array_filter($array, fn ($item) => $item);
        foreach ($array as $key => $value) {
            $value = is_array($value) ? $value : [$value];
            foreach ($value as  $id) {
                $user = User::find($id);
                Notification::send($user, new UpdatedNotification([
                    'type' => $type, 'key' => $key,
                    'previousValue' => $previousValue, 'currentValue' => $currentValue
                ]));
            }
        }
    }
}
