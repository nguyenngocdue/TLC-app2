<?php

namespace App\Mail;

// use App\Http\Controllers\Workflow\LibStatuses;
// use App\Utils\ConvertColorTailwind;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MailUpdatedDocument extends Mailable
{
    // use Queueable;
    use SerializesModels;

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
        // Log::info($this->data);
        return $this->markdown('mails.mail-updated-document', [
            'name' => $this->data['name'],
            'action' => $this->data['action'],

            'previousValue' => $this->data['previousValue'],
            'currentValue' => $this->data['currentValue'],
            'diff' => $this->data['diff'],

            'meta' => $this->data['meta'],
        ]);
    }
}
