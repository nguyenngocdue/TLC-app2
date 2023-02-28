<?php

namespace App\Notifications;

use App\Http\Controllers\Workflow\LibApps;
use App\Notifications\Traits\TraitSupportNotification;
use App\Utils\Support\Json\Definitions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class UpdatedNotification extends Notification
{
    use TraitSupportNotification;
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
        $data = $this->data['currentValue'];
        $type = $data['entity_type'];
        $status = $data['status'];
        $definitions = Definitions::getAllOf($type)['new'];
        array_shift($definitions);
        $arrayCheck = array_keys(array_filter($definitions, fn ($item) => $item));
        if (sizeof($arrayCheck) == 0 || in_array($status, $arrayCheck)) {
            return [];
        }
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        switch ($this->data['type']) {
            case 'assignee':
                // return $this->sendMailAssignee($this->data, $notifiable);
                break;
            case 'monitor':
                return $this->sendMailMonitors($this->data, $notifiable);
                break;

            default:
                # code...
                break;
        }
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
