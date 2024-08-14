<?php

namespace App\Mail\STS;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MailRemindStaff extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $data,
    ) {
        //
    }

    public function build()
    {
        // Log::info($this->data);
        $deadline =  (new DateTime())->modify('next Saturday')->format('d/m/Y');
        $this->data['deadline'] = $deadline;
        return $this->markdown('mails.sts.mail-remind-staff', $this->data);
    }
}
