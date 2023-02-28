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
    private function sendMailAssignee($data, $notifiable)
    {
        $previousValue = $data['previousValue'];
        $currentValue = $data['currentValue'];
        $type = $currentValue['entity_type'];
        $id = $currentValue['id'];
        $key = $data['key'];
        $monitorsPrevious = $previousValue[$key];
        $monitorsCurrent = $currentValue[$key];
        $creator = $currentValue['owner_id'];
        $cc = array_unique([$creator, ...$monitorsCurrent]);
        $listNameMonitorsPrevious = implode(',', array_values(User::whereIn('id', $monitorsPrevious)->pluck('name')->toArray()));
        $listNameMonitorsCurrent = implode(',', array_values(User::whereIn('id', $monitorsCurrent)->pluck('name')->toArray()));
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
            ->line('This document change assignee ' . $nameUserCreated . '.')
            ->action('View Document', url($href))
            ->line('Thank you for using our application!');
    }
    private function sendMailMonitors($data, $notifiable)
    {
        $currentValue = $data['currentValue'];
        $previousValue = $data['previousValue'];
        $type = $currentValue['entity_type'];
        $key = $data['key'];
        $monitorsPrevious = $previousValue[$key];
        $monitorsCurrent = $currentValue[$key];
        $creator = $currentValue['owner_id'];
        $cc = array_unique([$creator, ...$monitorsCurrent]);
        $listNameMonitorsPrevious = implode(',', array_values(User::whereIn('id', $monitorsPrevious)->pluck('name')->toArray()));
        $listNameMonitorsCurrent = implode(',', array_values(User::whereIn('id', $monitorsCurrent)->pluck('name')->toArray()));
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
            ->cc($cc)
            ->bcc($this->getMailBcc())
            ->greeting('Dear ' . $nameUserCreated . ',')
            ->line('This document change monitors ' . $listNameMonitorsPrevious . 'to' . $listNameMonitorsCurrent . '.')
            ->action('View Document', url($href))
            ->line('Thank you for using our application!');
    }

    private function getMailCc($type)
    {
        switch ($variable) {
            case 'value':
                # code...
                break;

            default:
                # code...
                break;
        }
    }
    private function getMailBcc()
    {
        return env('MAIL_ARCHIVE_BCC', 'info@gamil.com');
    }
}
