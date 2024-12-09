<?php

namespace App\Listeners\InspectionSignoff;

use App\Events\InspectionSignoff\SignOffSubmittedEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\MailUtility;
use App\Mail\MailSignOffSubmitted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SignOffSubmittedListener implements ShouldQueue
{
    use TraitSignOffListener;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(SignOffSubmittedEvent $event)
    {
        $mailContent = $event->mailContent;
        // Log::info($mailContent);
        $signableId = $event->signableId;
        $tableName = $event->tableName;
        try {
            $inspector = User::find($mailContent['user_id']);
            $monitors = User::whereIn('id', $mailContent['monitors1'])->get();
            $param = [
                'monitorNames' => $monitors->map(fn($u) => $u->first_name)->join(", "),
                "inspectorName" => $inspector->name,
                "signature_decision" => $mailContent['signature_decision'],
                "signature_comment" => $mailContent['signature_comment'],
            ];
            $param += $this->getMeta($tableName, $signableId);
            $mail = new MailSignOffSubmitted($param);

            // $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
            $meta = MailUtility::getMailTitle($tableName, $signableId, 'Request Sign Off');
            $subject = $meta['subject'];
            unset($meta['subject']);

            $mail->subject($subject);
            Mail::to($monitors)
                ->cc($inspector)
                ->bcc(env('MAIL_ARCHIVE_BCC'))
                ->send($mail);
        } catch (\Exception $e) {
            Log::error("SignOffSubmittedListener: " . $e->getMessage() . $e->getFile() . $e->getLine());
            // $msg = "Mail to <b>{$receiver->email}</b> failed.<br/>";
            // $msg .= $e->getMessage();
            // $msg .= $e->getFile() . " (Line: " . $e->getLine() . ")";
            // broadcast(new WssToastrMessageChannel([
            //     'wsClientId' => $data['wsClientId'],
            //     'type' => 'error',
            //     'message' => $msg,
            // ]));
            // return $msg;
        }
    }
}
