<?php

namespace App\Listeners;

use App\Events\SignOffRecallEvent;
use App\Events\SignOffSubmittedEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\TraitSignOffListener;
use App\Mail\MailSignOffRecall;
use App\Mail\MailSignOffSubmitted;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        // Log::info($mailContent);
        $mailContent = $event->mailContent;
        $signableId = $event->signableId;
        $tableName = $event->tableName;
        try {
            $inspector = User::find($mailContent['user_id']);
            $monitors = User::whereIn('id', $mailContent['monitors1'])->get();
            $param = ['monitorNames' => $monitors->map(fn ($u) => $u->name)->join(", "), "inspectorName" => $inspector->name];
            $param += $this->getMeta($tableName, $signableId);
            // $params = ['receiverName' => $receiver->name, 'requesterName' => $requester->name,];
            // $params += $this->getMeta($data);
            $mail = new MailSignOffSubmitted($param);
            $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
            $mail->subject($subject);
            Mail::to($monitors)
                ->cc($inspector)
                ->bcc(env('MAIL_ARCHIVE_BCC'))
                ->send($mail);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . $e->getFile() . $e->getLine());
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
