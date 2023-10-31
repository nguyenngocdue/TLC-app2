<?php

namespace App\Listeners;

use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\SendMailChangeStatus;
use App\Models\User;
use App\Utils\SendMaiAndNotification\CheckDefinitionsNew;
use App\Utils\Support\Json\BallInCourts;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $previousValue = $event->{'previousValue'};
        $currentValue = $event->{'currentValue'};
        $id = $currentValue['id'];
        $type = $event->{'type'};
        if (!isset($currentValue['status']) || !isset($previousValue['status'])) {
            Log::error("$type Status NOT FOUND, Send Mail And Notifications will not work.");
            return;
        }
        try {
            $ballInCourts = BallInCourts::getAllOf($type);
            $previousStatus = $previousValue['status'];
            $currentStatus = $currentValue['status'];
            [$userAssigneePrevious, $userMonitorsPrevious, $userAssigneeCurrent, $userMonitorsCurrent] =
                $this->getUserIdsAssigneeAndMonitors($ballInCourts, $previousValue, $currentValue, $previousStatus, $currentStatus);
            [$isChangeStatus, $userAssigneeCurrent, $listCc, $isChangeAssignee, $isChangeMonitors] =
                $this->handleBallInCourtForAssigneeAndMonitors(
                    $previousStatus,
                    $currentStatus,
                    $userAssigneePrevious,
                    $userMonitorsPrevious,
                    $userAssigneeCurrent,
                    $userMonitorsCurrent
                );
            if ($isChangeStatus || $isChangeAssignee || $isChangeMonitors) {
                $this->sendMail(
                    $isChangeStatus,
                    $userAssigneeCurrent,
                    $listCc,
                    $isChangeAssignee,
                    $isChangeMonitors,
                    $type,
                    $id,
                    $previousValue,
                    $currentValue,
                    $userAssigneePrevious,
                    $userMonitorsPrevious,
                    $userMonitorsCurrent
                );
            }
        } catch (\Throwable $th) {
            Toastr::warning($th->getMessage(), 'Send mail failed');
        }
    }
    private function sendMail(
        $isChangeStatus,
        $userAssigneeCurrent,
        $listCc,
        $isChangeAssignee,
        $isChangeMonitors,
        $type,
        $id,
        $previousValue,
        $currentValue,
        $userAssigneePrevious,
        $userMonitorsPrevious,
        $userMonitorsCurrent
    ) {
        $user = $userAssigneeCurrent ? User::findFromCache($userAssigneeCurrent) : User::findFromCache(1);
        [$href, $subjectMail] = $this->getRouteAndSubjectMail($type, $id);
        $nameOldAssignee = User::findFromCache($userAssigneePrevious)->name ?? '';
        $stringNameMonitorsPrevious = $this->implodeNameMonitors($userMonitorsPrevious);
        $stringNameMonitorsCurrent = $this->implodeNameMonitors($userMonitorsCurrent);
        Mail::to($user)
            ->cc($this->getAddressCc($listCc, $user, $currentValue))
            ->bcc($this->getMailBcc())
            ->send(new SendMailChangeStatus([
                'type' => $type,
                'name' => $user['name'],
                'subject' => $subjectMail,
                'action' => url($href),
                'isChangeStatus' => $isChangeStatus,
                'changeAssignee' => $isChangeAssignee ? ['previous' => $nameOldAssignee, 'current' => $user['name']] : null,
                'changeMonitor' => $isChangeMonitors ? ['previous' => $stringNameMonitorsPrevious, 'current' => $stringNameMonitorsCurrent] : null,
                'currentValue' => $currentValue,
                'previousValue' => $previousValue,
            ]));
    }
    private function getUserIdsAssigneeAndMonitors($ballInCourts, $previousValue, $currentValue, $previousStatus, $currentStatus)
    {
        $ballInCourtPrevious = $ballInCourts[$previousStatus];
        $ballInCourtCurrent = $ballInCourts[$currentStatus];
        $keyAssigneePrevious = $ballInCourtPrevious['ball-in-court-assignee'];
        $keyMonitorsPrevious = $ballInCourtPrevious['ball-in-court-monitors'];
        $keyAssigneeCurrent = $ballInCourtCurrent['ball-in-court-assignee'];
        $keyMonitorsCurrent = $ballInCourtCurrent['ball-in-court-monitors'];
        $userAssigneePrevious = $previousValue[$keyAssigneePrevious] ?? '';
        $userMonitorsPrevious = $previousValue[$keyMonitorsPrevious] ?? [];
        $userAssigneeCurrent = $currentValue[$keyAssigneeCurrent] ?? '';
        $userMonitorsCurrent = $currentValue[$keyMonitorsCurrent] ?? [];
        return [$userAssigneePrevious, $userMonitorsPrevious, $userAssigneeCurrent, $userMonitorsCurrent];
    }
    private function handleBallInCourtForAssigneeAndMonitors(
        $previousStatus,
        $currentStatus,
        $userAssigneePrevious,
        $userMonitorsPrevious,
        $userAssigneeCurrent,
        $userMonitorsCurrent
    ) {
        $listCc = [];
        $isChangeStatus = false;
        $isChangeAssignee = false;
        $isChangeMonitors = false;
        if ($previousStatus === $currentStatus) {
            $this->handleProcessSendMailByBallInCourt(
                $listCc,
                $isChangeAssignee,
                $isChangeMonitors,
                $userAssigneePrevious,
                $userAssigneeCurrent,
                $userMonitorsPrevious,
                $userMonitorsCurrent,
                $isChangeStatus
            );
        } else {
            $isChangeStatus = true;
            $this->handleProcessSendMailByBallInCourt(
                $listCc,
                $isChangeAssignee,
                $isChangeMonitors,
                $userAssigneePrevious,
                $userAssigneeCurrent,
                $userMonitorsPrevious,
                $userMonitorsCurrent,
                $isChangeStatus
            );
        }
        return [$isChangeStatus, $userAssigneeCurrent, $listCc, $isChangeAssignee, $isChangeMonitors];
    }
    private function handleProcessSendMailByBallInCourt(
        &$listCc,
        &$isChangeAssignee,
        &$isChangeMonitors,
        $userAssigneePrevious,
        $userAssigneeCurrent,
        $userMonitorsPrevious,
        $userMonitorsCurrent,
        $isChangeStatus = false
    ) {
        if ($userAssigneePrevious != $userAssigneeCurrent) {
            $listCc[] = $userAssigneePrevious;
            $isChangeAssignee = true;
        }
        if ($userMonitorsPrevious != $userMonitorsCurrent) {
            $listCc = array_merge($listCc, $userMonitorsPrevious, $userMonitorsCurrent);
            $isChangeMonitors = true;
        } else {
            if ($isChangeStatus) {
                $listCc = array_merge($listCc, $userMonitorsCurrent);
            }
        }
    }
    private function implodeNameMonitors($ids)
    {
        return implode(',', array_values(User::whereIn('id', $ids)->pluck('name0')->toArray()));
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
    private function getAddressCc($array, $user, $currentValue)
    {
        $ownerId = $currentValue['owner_id'];
        $array[] = $ownerId;
        $idCc = array_unique($array);
        $ccEmails = array_values(User::whereIn('id', $idCc)->pluck('email')->toArray());
        return $this->filterCc($ccEmails, $user);
    }
    private function filterCc($cc, $user)
    {
        return array_filter($cc, fn ($item) => $item !== $user['email']);
    }
    private function getMailBcc()
    {
        return env('MAIL_ARCHIVE_BCC', 'info@gamil.com');
    }
    private function isArraysDiffer($array1, $array2)
    {
        $diff = array_diff($array1, $array2);
        dd($array1 == $array2);
        if (empty($diff)) return false;
        return true;
    }
    private function compareArrays($a, $b)
    {
        sort($a);
        sort($b);
        return $a == $b;
    }
}
