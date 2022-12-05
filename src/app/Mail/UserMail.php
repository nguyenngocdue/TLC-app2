<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $dataEvent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataEvent)
    {
        $this->dataEvent = $dataEvent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $dataEvent = $this->dataEvent;
        return $this->view('mails.userMail')->with(compact('dataEvent'));
    }
}
