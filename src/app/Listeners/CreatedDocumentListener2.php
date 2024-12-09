<?php

namespace App\Listeners;

use App\Events\CreatedDocumentEvent2;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\MailCreateNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreatedDocumentListener2 implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function sendMail($item, $type, $id)
    {
        $app = LibApps::getFor($type);
        if ($app['do_not_send_notification_mails'] ?? false) return;

        // $nickname = strtoupper($app['nickname'] ?: $app['name']);
        // $appTitle = $app['title'];
        // $subject = "[$nickname/$id] - $appTitle - " . env("APP_NAME");

        $meta = MailUtility::getMailTitle($type, $id);
        $subject = $meta['subject'];
        unset($meta['subject']);

        $creator = $item->getOwner;
        $mail = new MailCreateNew([
            'name' => $creator->name,
            'url' => route(Str::plural($type) . ".edit", $id),
        ]);
        $mail->subject($subject);

        Mail::to($creator->email)
            ->bcc(env('MAIL_ARCHIVE_BCC'))
            ->send($mail);
    }

    public function handle(CreatedDocumentEvent2 $event)
    {
        // Log::info(json_encode($event));
        $modelPath = $event->modelPath;
        $id = $event->id;
        $type = $event->type;
        $item = $modelPath::find($id);
        $this->sendMail($item, $type, $id);
    }
}
