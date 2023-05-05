<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindSignOffNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public $doc,
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
        // dump($notifiable);
        // dd($this->data);
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
        [
            'signable_type' => $signable_type,
            'signable_id' => $signable_id,
            'requester' => $requester,
        ] = $this->doc;
        // dump($requester);
        return (new MailMessage)
            ->cc($requester['email'])
            ->bcc(env('MAIL_ARCHIVE_BCC'))

            ->subject("[ICS/$signable_id] Inspection Checklist - " . config("company.name") . " APP")
            ->greeting("Dear $name,")
            ->line($requester['name'] . ' have send a request to you to sign off a document.')
            ->line('Please click the button below to open the sign off page.')
            ->action('Sign Off Now', url("/dashboard/$signable_type/$signable_id/edit"))
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
