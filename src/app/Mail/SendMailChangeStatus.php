<?php

namespace App\Mail;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\ConvertColorTailwind;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailChangeStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $data)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $oldStatus = $data['previousValue']['status'];
        $newStatus = $data['currentValue']['status'];
        $appName = config("company.name");
        $changeAssignee = $data['changeAssignee'];
        $changeMonitor = $data['changeMonitor'];
        return $this->subject($data['subject'])->markdown('emails.mail-change-status', [
            'appName' => $appName,
            'name' => $data['name'],
            'action' => $data['action'],
            'oldStatus' => $oldStatus,
            'newStatus' => $newStatus,
            'changeAssignee' => $changeAssignee,
            'changeMonitor' => $changeMonitor,
        ]);
    }
}
