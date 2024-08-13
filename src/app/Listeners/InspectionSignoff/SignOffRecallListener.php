<?php

namespace App\Listeners\InspectionSignoff;

use App\Events\InspectionSignoff\SignOffRecallEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\MailUtility;
use App\Mail\MailSignOffRecall;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SignOffRecallListener implements ShouldQueue
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

    public function handle(SignOffRecallEvent $event)
    {
        $data = $event->data;
        $tableName = $data['tableName'];
        $signableId = $data['signableId'];
        $requester = User::find($data['requesterId']);
        $signatureIds = $data['signatureIds'];
        $signatures = Signature::whereIn('id', $signatureIds)->get();
        // $receivers = array_map(fn ($sig) => User::find($sig->user_id), $signatures);
        // [$requester,] = $this->getUsers($data);

        foreach ($signatures as $sig) {
            // Log::info($receiver);
            $receiver = User::find($sig->user_id);
            try {
                $params = ['receiverName' => $receiver->name, 'requesterName' => $requester->name,];
                $params += $this->getMeta($tableName, $signableId);
                $mail = new MailSignOffRecall($params);
                // $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
                $subject = MailUtility::getMailTitle($tableName, $signableId, 'Request Sign Off');

                $mail->subject($subject);
                Mail::to($receiver->email)
                    ->cc($requester->email)
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            } catch (\Exception $e) {
                $msg = "Mail to <b>{$receiver->email}</b> failed.<br/>";
                $msg .= $e->getMessage();
                $msg .= $e->getFile() . " (Line: " . $e->getLine() . ")";
                broadcast(new WssToastrMessageChannel([
                    'wsClientId' => $data['wsClientId'],
                    'type' => 'error',
                    'message' => $msg,
                ]));
                return $msg;
            }

            // Log::info("Deleting " . $sig->id);
            $sig->forceDelete(); //Hard delete

            broadcast(new WssToastrMessageChannel([
                'wsClientId' => $data['wsClientId'],
                'type' => 'success',
                'message' => "Recall email sent to <b>{$receiver->email}</b> sent successfully.",
            ]));
        }

        // return "OK";
    }
}
