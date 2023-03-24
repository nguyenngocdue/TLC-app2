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
}
