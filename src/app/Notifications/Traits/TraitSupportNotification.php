<?php

namespace App\Notifications\Traits;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TraitSupportNotification
{


    private function sendMailCreate($data, $notifiable, $create = true)
    {
        $currentValue = $data['currentValue'];
        $type = $currentValue['entity_type'];
        $id = $currentValue['id'];
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $id) : "#";
        $nameUserCreated = $notifiable['name'];
        $libApps = LibApps::getFor($type);
        $nickNameEntity = strtoupper($libApps['nickname'] ?? $type);
        $titleEntity = $libApps['title'];
        $subjectMail = '[' . $nickNameEntity . '/' . $id . '] ' . $nameUserCreated . ' - ' . $titleEntity . ' - ' . 'TLC Modular APP';
        return (new MailMessage)
            ->subject($subjectMail)
            ->greeting('Dear ' . $nameUserCreated . ',')
            ->line('This document has been created by ' . $nameUserCreated . '.')
            ->action('View Document', url($href))
            ->line('Thank you for using our application!');
    }
    private function sendMailUpdate($data, $typeSend, $notifiable)
    {
        $currentValue = $data['currentValue'];
        $previousValue = $data['previousValue'];
        $type = $currentValue['entity_type'];
        $creator = $currentValue['owner_id'];
        [$keyOldAssignee, $keyNewAssignee, $keyOldMonitors, $keyNewMonitors] = $this->getKeyBallInCourt($data);
        $monitorsPrevious = $previousValue[$keyOldMonitors];
        $monitorsCurrent = $currentValue[$keyNewMonitors];
        $id = $currentValue['id'];
        [$href, $subjectMail] = $this->getRouteAndSubjectMail($type, $id, $notifiable);
        switch ($typeSend) {
            case 'assignee':
                $keyOldAssignee = $keyOldAssignee == 'creator' ? 'assignee_1' : $keyOldAssignee;
                $assigneeOldBallInCourt = $previousValue[$keyOldAssignee];
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent, $assigneeOldBallInCourt);
                $cc = array_filter($cc, fn ($item) => !$item == $notifiable['email']);
                $nameOldAssignee = array_values(User::where('id', $assigneeOldBallInCourt)->pluck('name')->toArray());
                return (new MailMessage)
                    ->subject($subjectMail)
                    ->cc($cc)
                    ->bcc($this->getMailBcc())
                    ->greeting('Dear ' . $notifiable['name'] . ',')
                    ->line('This document change assignee ' . $nameOldAssignee[0] . ' to ' . $notifiable['name'] . '.')
                    ->action('View Document', url($href))
                    ->line('Thank you for using our application!');
                break;
            case 'monitors':
                $cc = $this->getMailCc($typeSend, $creator, $monitorsCurrent);
                $cc = array_filter($cc, fn ($item) => !$item == $notifiable['email']);
                $listNameMonitorsPrevious = implode(',', array_values(User::whereIn('id', $monitorsPrevious)->pluck('name')->toArray()));
                $listNameMonitorsCurrent = implode(',', array_values(User::whereIn('id', $monitorsCurrent)->pluck('name')->toArray()));
                return (new MailMessage)
                    ->subject($subjectMail)
                    ->cc($cc)
                    ->bcc($this->getMailBcc())
                    ->greeting('Dear ' . $notifiable['name'] . ',')
                    ->line('This document change monitors ' . $listNameMonitorsPrevious . ' to ' . $listNameMonitorsCurrent . '.')
                    ->action('View Document', url($href))
                    ->line('Thank you for using our application!');
                break;
            default:
                break;
        }
    }
    private function getRouteAndSubjectMail($type, $id, $notifiable)
    {
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $id) : "#";
        $libApps = LibApps::getFor($type);
        $nickNameEntity = strtoupper($libApps['nickname'] ?? $type);
        $titleEntity = $libApps['title'];
        $subjectMail = '[' . $nickNameEntity . '/' . $id . '] ' .  ' - ' . $titleEntity . ' - ' . 'TLC Modular APP';
        return [$href, $subjectMail];
    }
    private function getKeyBallInCourt($data)
    {
        $ballInCourt = explode('|', $data['key']);
        $keyOldAssigneeBallInCourt = $ballInCourt[0];
        $keyNewAssigneeBallInCourt = $ballInCourt[2];
        $keyOldMonitorsBallInCourt = $ballInCourt[1];
        $keyNewMonitorsBallInCourt = $ballInCourt[3];
        return [$keyOldAssigneeBallInCourt, $keyNewAssigneeBallInCourt, $keyOldMonitorsBallInCourt, $keyNewMonitorsBallInCourt];
    }
    private function getMailCc($type, $creator, $monitorsCurrent, $assigneeOldBallInCourt = null)
    {
        switch ($type) {
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
