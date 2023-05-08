<?php

namespace App\Mail;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailInspector extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $appName = config("company.name");
        return $this->subject($data['subject'])->view('mails.mail-send-inspector', [
            'appName' => $appName,
            'name' => $data['name'],
            'action' => $data['action'],
        ]);
    }
}
