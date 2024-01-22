<?php

namespace App\Listeners;

use App\Events\SignOffRemindEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\TraitSignOffListener;
use App\Mail\MailSignOffRemind;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SignOffRemindListener //implements ShouldQueue
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

    public function handle(SignOffRemindEvent $event)
    {
        $tableName = 'qaqc_insp_chklst_shts';
        $signatures = Signature::query()
            ->where('signable_type', Qaqc_insp_chklst_sht::class)
            ->where('signature_decision', null)
            ->with(['getUser'])
            ->get();
        foreach ($signatures as $signature) {
            if (is_null($signature->signable)) {
                Log::info("Signature #" . $signature->id . " - signable [" . $signature->signable_id . "] not found.");
                continue;
            }
            if ($signature->signable->status != 'pending_audit') continue;


            $requester = User::findFromCache($signature->owner_id);
            $receiver = $signature->getUser;
            $signableId = $signature->signable_id;
            try {
                $params = ['receiverName' => $receiver->name, 'requesterName' => $requester->name,];
                $params += $this->getMeta($tableName, $signableId);
                $mail = new MailSignOffRemind($params);
                $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
                $mail->subject($subject);
                Mail::to($receiver->email)
                    ->cc($requester->email)
                    ->bcc(env('MAIL_ARCHIVE_BCC'))
                    ->send($mail);
            } catch (\Exception $e) {
                Log::error("Error during SignOffRemindListener:");
                Log::error($e->getMessage() . $e->getFile() . $e->getLine());
            }
        }
    }
}
