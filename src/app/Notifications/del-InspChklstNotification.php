<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InspChklstNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public $owner,
        public $dataNoOrFail,
        public $dataComment,
        public $inspChklstId,
        public $url,
        public $monitors
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = $notifiable->name;
        $inspChklstId = $this->inspChklstId;
        $monitors = $this->monitors;
        $data = [
            'url' => $this->url,
            'dataNoOrFail' => $this->dataNoOrFail,
            'dataComment' => $this->dataComment,
            'creator' => $this->owner->name,
        ];
        $data['variables'] = array_keys($data);
        return (new MailMessage)
            ->cc($monitors)
            ->bcc(env('MAIL_ARCHIVE_BCC'))
            ->subject("[ICS/$inspChklstId] Inspection Checklist - " . config("company.name") . " APP")
            ->greeting("Dear $name,")
            ->line('You have send a request to you to sign off a document.')
            ->line('Please click the button below to open the Insp Checklist Sheet page.')
            ->action('Sign Off Now', url($this->url))
            ->markdown('mails.insp-chklst', $data)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
