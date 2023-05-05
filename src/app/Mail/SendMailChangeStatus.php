<?php

namespace App\Mail;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailChangeStatus extends Mailable implements ShouldQueue
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
        $libStatuses = LibStatuses::getFor($data['type']);
        $oldStatus = $data['previousValue']['status'];
        $colorOldStatus = $libStatuses[$oldStatus]['color'];
        $colorIndexOldStatus = $libStatuses[$oldStatus]['color_index'];
        $oldStatus = $libStatuses[$oldStatus]['title'];
        $newStatus = $data['currentValue']['status'];
        $colorNewStatus = $libStatuses[$newStatus]['color'];
        $colorIndexNewStatus = $libStatuses[$newStatus]['color_index'];
        $newStatus = $libStatuses[$newStatus]['title'];
        $appName = config("company.name");
        $changeAssignee = $data['changeAssignee'];
        $changeMonitor = $data['changeMonitor'];
        return $this->subject($data['subject'])->view('mails.mail-change-status', [
            'appName' => $appName,
            'name' => $data['name'],
            'action' => $data['action'],
            'oldStatus' => $oldStatus,
            'colorOldStatus' => $colorOldStatus,
            'colorIndexOldStatus' => $colorIndexOldStatus,
            'newStatus' => $newStatus,
            'colorNewStatus' => $colorNewStatus,
            'colorIndexNewStatus' => $colorIndexNewStatus,
            'changeAssignee' => $changeAssignee,
            'changeMonitor' => $changeMonitor,
        ]);
    }
}
