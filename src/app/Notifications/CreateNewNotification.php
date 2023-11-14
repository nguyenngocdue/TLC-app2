<?php

namespace App\Notifications;

use App\Http\Controllers\Workflow\LibApps;
use App\Mail\MailCreateNew;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CreateNewNotification extends Notification
{
    use Queueable;
    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $data = $this->data;
        if ($data['currentValue']['status']) {
            return ['database', 'mail'];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $currentValue = $this->data['currentValue'];
        $type = $currentValue['entity_type'];
        $id = $currentValue['id'];
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits = (Route::has($routeName));
        $url =  $routeExits ? route($routeName, $id) : "#";
        $nameUserCreated = $notifiable['name'];
        $libApps = LibApps::getFor($type);
        $nickNameEntity = strtoupper($libApps['nickname'] ?? $type);
        $titleEntity = $libApps['title'];
        $subjectMail = '[' . $nickNameEntity . '/' . $id . '] ' . $nameUserCreated . ' - ' . $titleEntity . ' - ' . config("company.name") . ' APP';

        $mail = (new MailCreateNew(['name' => $nameUserCreated, 'url' => $url,]))
            ->to($notifiable['email'])
            ->subject($subjectMail)
            ->bcc(env('MAIL_ARCHIVE_BCC'));
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }
}
