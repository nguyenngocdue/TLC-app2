<?php

namespace App\Listeners;

use App\Events\BroadcastEvents\BroadcastNotificationEvent;
use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\SendMailChangeStatus;
use App\Models\Logger;
use App\Models\User;
use App\Notifications\UpdatedNotification;
use App\Utils\Constant;
use App\Utils\SendMaiAndNotification\CheckDefinitionsNew;
use App\Utils\Support\Json\BallInCourts;
use App\Utils\Support\JsonControls;
use Barryvdh\Debugbar\Twig\Extension\Dump;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SendUpdatedDocumentNotificationListener implements ShouldQueue
{
    use CheckDefinitionsNew;
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
        $changStatusAssignee = [];
        $changStatusMonitors = [];
        $previousValue = $event->{'previousValue'};
        $currentValue = $event->{'currentValue'};
        $modelPath = $event->{'modelPath'};
        $userCurrentId = $event->{'userCurrentId'};
        if (!$currentValue['status']) {
            dump('Status NOT FOUND.');
            dump('Send Mail And Notifications will not work.');
            return;
        }
        $listAssignees = JsonControls::getAssignees();
        $listMonitors = JsonControls::getMonitors();
        foreach ($currentValue as $key => $value) {
            switch ($key) {
                case in_array($key,$listAssignees):
                    $assigneePrevious = $previousValue[$key] ?? null;
                    [$isChange, $outBallInCourt, $newBallInCourt] =
                        $this->isChangeBallInCourt($previousValue, $currentValue, $listAssignees, $listMonitors);
                    $keyCombine = $outBallInCourt . '|' . $newBallInCourt;
                    $value == $assigneePrevious ? $assigneeNotification[$keyCombine] = null
                        : $assigneeNotification[$keyCombine] = $value;
                    if ($previousValue['status'] !== $currentValue['status']) {
                        if ($isChange) {
                            if (explode('|', $newBallInCourt)[0] == 'creator') {
                                $changStatusAssignee[$keyCombine]
                                    = $currentValue['owner_id'];
                            }
                        }
                        $changStatusAssignee[$keyCombine]
                            = $value;
                    }
                    break;
                case in_array($key, $listMonitors):
                    $monitorPrevious = $previousValue[$key] ?? [];
                    [$isChange, $outBallInCourt, $newBallInCourt] =
                        $this->isChangeBallInCourt($previousValue, $currentValue, $listAssignees, $listMonitors, 1);
                    $keyCombine = $outBallInCourt . '|' . $newBallInCourt;
                    $monitorNotification[$keyCombine] = array_diff($value, $monitorPrevious);
                    if ($previousValue['status'] !== $currentValue['status']) {
                        if ($isChange) {
                            $changStatusMonitors[$outBallInCourt . '|' . $newBallInCourt]
                                = $value;
                        }
                    }
                    break;
                case 'owner_id':

                    break;
                default:
                    break;
            }
        }
        dump($changStatusAssignee);
        $isNullAssigneeArrayValue = $this->isNullAssigneeArrayValue($assigneeNotification);
        $isNullAssigneeArrayValue ?
            $this->send($assigneeNotification, $previousValue, $currentValue, 'assignee')
            : $this->send($changStatusAssignee, $previousValue, $currentValue, 'assignee_status_change');
        empty($monitorNotification) ?
            $this->send($changStatusMonitors, $previousValue, $currentValue, 'monitor_status_change')
            : $this->send($monitorNotification, $previousValue, $currentValue, 'monitors');
    }
    private function isNullAssigneeArrayValue($array)
    {
        $arrayValue = array_values($array);
        return array_shift($arrayValue);
    }
    private function isChangeBallInCourt($previousValue, $currentValue, $listAssignees, $listMonitors, $index = 0)
    {
        $typeBallInCourt = [
            'ball-in-court-assignee',
            'ball-in-court-monitors',
        ];
        $previousStatus = $previousValue['status'];
        $currentStatus = $currentValue['status'];
        $type = $currentValue['entity_type'];

        $ballInCourts = array_map(function ($item) use ($listAssignees, $listMonitors, $typeBallInCourt) {
            if (!$item[$typeBallInCourt[0]]) {
                $item[$typeBallInCourt[0]] = $listAssignees[0];
            }
            if (!$item[$typeBallInCourt[1]]) {
                $item[$typeBallInCourt[1]] = $listMonitors[0];
            }
            return $item;
        }, BallInCourts::getAllOf($type));
        $outBallInCourt = $ballInCourts[$previousStatus];
        $newBallInCourt = $ballInCourts[$currentStatus];
        $result1 = $outBallInCourt[$typeBallInCourt[0]] . '|' . $outBallInCourt[$typeBallInCourt[1]];
        $result2 = $newBallInCourt[$typeBallInCourt[0]] . '|' . $newBallInCourt[$typeBallInCourt[1]];
        if ($newBallInCourt[$typeBallInCourt[$index]] == $outBallInCourt[$typeBallInCourt[$index]]) {
            return [false, $result1, $result2];
        }
        return [true, $result1, $result2];
    }
    private function send($array, $previousValue, $currentValue, $type, $sendNotification = true)
    {
        $array = array_filter($array, fn ($item) => $item);
        foreach ($array as $key => $value) {
            $value = is_array($value) ? $value : [$value];
            foreach ($value as  $id) {
                $user = User::find($id);
                if ($sendNotification) {
                    $this->sendNotificationAndMail($user, $type, $key, $previousValue, $currentValue);
                } else {
                    $this->sendMail($user, $type, $key, $previousValue, $currentValue);
                }
            }
        }
    }
    private function sendNotificationAndMail($user, $type, $key, $previousValue, $currentValue)
    {
        Notification::send($user, new UpdatedNotification(
            [
                'type' => $type,
                'key' => $key,
                'currentValue' => $currentValue,
                'previousValue' => $previousValue,
            ]
        ));
        // broadcast(new BroadcastNotificationEvent(auth()->user()));
        // $isDefinitionNew = $this->isDefinitionNew($currentValue);
        // if (!$isDefinitionNew) {
        //     $this->sendMail($user, $type, $key, $previousValue, $currentValue);
        // }
        $this->sendMail($user, $type, $key, $previousValue, $currentValue);

    }
    private function sendMail($user, $typeSend, $key, $previousValue, $currentValue)
    {
        $type = $currentValue['entity_type'];
        $creator = $currentValue['owner_id'];
        [$keyOldAssignee, $keyNewAssignee, $keyOldMonitors, $keyNewMonitors] = $this->getKeyBallInCourt($key);
        $monitorsCurrent = $currentValue[$keyNewMonitors] ?? [];
        $monitorsPrevious = $previousValue[$keyOldMonitors] ?? [];
        $id = $currentValue['id'];
        [$href, $subjectMail] = $this->getRouteAndSubjectMail($type, $id);
        switch ($typeSend) {
            case 'assignee_status_change':
                $keyOldAssignee = $this->keyOldAssignee($keyOldAssignee);
                $assigneeOldBallInCourt = $previousValue[$keyOldAssignee] ?? [];
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent, $assigneeOldBallInCourt);
                $cc = $this->filterCc($cc, $user);
                $this->sendMailFormat(
                    $user,
                    $cc,
                    $type,
                    $user['name'],
                    $subjectMail,
                    $href,
                    $currentValue,
                    $previousValue,
                    null,
                    null
                );
                break;
            case 'monitor_status_change':
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent);
                $cc = $this->filterCc($cc, $user);
                $this->sendMailFormat(
                    $user,
                    $cc,
                    $type,
                    $user['name'],
                    $subjectMail,
                    $href,
                    $currentValue,
                    $previousValue,
                    null,
                    null
                );
                break;
            case 'assignee':
                $keyOldAssignee = $this->keyOldAssignee($keyOldAssignee);
                $assigneeOldBallInCourt = $previousValue[$keyOldAssignee];
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent, $assigneeOldBallInCourt);
                $cc = $this->filterCc($cc, $user);
                $nameOldAssignee = array_values(User::where('id', $assigneeOldBallInCourt)->pluck('name')->toArray());
                $this->sendMailFormat(
                    $user,
                    $cc,
                    $type,
                    $user['name'],
                    $subjectMail,
                    $href,
                    $currentValue,
                    $previousValue,
                    ['previous' => $nameOldAssignee[0] ?? '', 'current' => $user['name']],
                    null
                );
                break;
            case 'monitors':
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent);
                $cc = $this->filterCc($cc, $user);
                $stringNameMonitorsPrevious = $this->implodeNameMonitors($monitorsPrevious);
                $stringNameMonitorsCurrent = $this->implodeNameMonitors($monitorsCurrent);
                $this->sendMailFormat(
                    $user,
                    $cc,
                    $type,
                    $user['name'],
                    $subjectMail,
                    $href,
                    $currentValue,
                    $previousValue,
                    null,
                    ['previous' => $stringNameMonitorsPrevious, 'current' => $stringNameMonitorsCurrent]
                );
                break;
            default:
                break;
        }
    }
    private function keyOldAssignee($key)
    {
        return $key == 'creator' ? 'assignee_1' : $key;
    }
    private function filterCc($cc, $user)
    {
        return array_filter($cc, fn ($item) => $item !== $user['email']);
    }

    private function sendMailFormat($user, $cc, $type, $userName, $subjectMail, $href, $currentValue, $previousValue, $changeAssignee, $changeMonitor)
    {
        Mail::to($user)
            ->cc($cc)
            ->bcc($this->getMailBcc())
            ->send(new SendMailChangeStatus([
                'type' => $type,
                'name' => $user['name'],
                'subject' => $subjectMail,
                'action' => url($href),
                'changeAssignee' => $changeAssignee,
                'changeMonitor' => $changeMonitor,
                'currentValue' => $currentValue,
                'previousValue' => $previousValue,
            ]));
    }
    private function implodeNameMonitors($array)
    {
        return implode(',', array_values(User::whereIn('id', $array)->pluck('name')->toArray()));
    }
    private function getKeyBallInCourt($key)
    {
        $ballInCourt = explode('|', $key);
        $keyOldAssigneeBallInCourt = $ballInCourt[0];
        $keyNewAssigneeBallInCourt = $ballInCourt[2];
        $keyOldMonitorsBallInCourt = $ballInCourt[1];
        $keyNewMonitorsBallInCourt = $ballInCourt[3];
        return [$keyOldAssigneeBallInCourt, $keyNewAssigneeBallInCourt, $keyOldMonitorsBallInCourt, $keyNewMonitorsBallInCourt];
    }
    private function getRouteAndSubjectMail($type, $id)
    {
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $id) : "#";
        $libApps = LibApps::getFor($type);
        $nickNameEntity = strtoupper($libApps['nickname'] ?? $type);
        $titleEntity = $libApps['title'];
        $subjectMail = '[' . $nickNameEntity . '/' . $id . '] ' .  ' - ' . $titleEntity . ' - ' . config("company.name") . ' Modular APP';
        return [$href, $subjectMail];
    }
    private function getMailCc($type, $creator, $monitorsCurrent, $assigneeOldBallInCourt = null)
    {
        $monitorsCurrent = is_array($monitorsCurrent) ? $monitorsCurrent : [$monitorsCurrent];
        try {
            switch ($type) {
                case 'assignee_status_change':
                    $array = [$creator, $assigneeOldBallInCourt, ...$monitorsCurrent];
                    return $this->getAddressCc($array);
                    break;
                case 'monitor_status_change':
                    $array = [$creator, ...$monitorsCurrent];
                    return $this->getAddressCc($array);
                    break;
                case 'assignee':
                    $array = [$creator, $assigneeOldBallInCourt, ...$monitorsCurrent];
                    return $this->getAddressCc($array);
                    break;
                case 'monitors':
                    $array = [$creator, ...$monitorsCurrent];
                    return $this->getAddressCc($array);
                    break;
                default:
                    break;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }
    private function getAddressCc($array)
    {
        $idCc = array_unique($array);
        return array_values(User::whereIn('id', $idCc)->pluck('email')->toArray());
    }
    private function getMailBcc()
    {
        return env('MAIL_ARCHIVE_BCC', 'info@gamil.com');
    }
}
