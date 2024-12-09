<?php

namespace App\Listeners\InspectionSignoff;

use App\Events\InspectionSignoff\SignOffRequestEvent;
use App\Events\WssToastrMessageChannel;
use App\Listeners\MailUtility;
use App\Mail\MailSignOffRequest;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SignOffRequestListener implements ShouldQueue
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

    private function getUsers($data)
    {
        $requester = User::find($data['requesterId']);
        $receivers = array_map(fn($uid) => User::find($uid), $data['uids']);
        $category_id = FieldSeeder::getIdFromFieldName($data['category']);
        return [$requester, $receivers, $category_id];
    }

    public function handle(SignOffRequestEvent $event)
    {
        $data = $event->data;
        $tableName = $data['tableName'];
        $signableId = $data['signableId'];
        [$requester, $receivers, $category_id] = $this->getUsers($data);

        foreach ($receivers as $receiver) {
            // Log::info($receiver);
            try {
                $params = ['receiverName' => $receiver->name, 'requesterName' => $requester->name,];
                $params += $this->getMeta($tableName, $signableId);
                $mail = new MailSignOffRequest($params);

                // $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
                $meta = MailUtility::getMailTitle($tableName, $signableId, 'Request Sign Off');
                $subject = $meta['subject'];
                unset($meta['subject']);

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

            Signature::create([
                'user_id' => $receiver->id,
                'owner_id' => $requester->id,
                'signable_type' => Str::modelPathFrom($data['tableName']),
                'signable_id' => $data['signableId'],
                'category' => $category_id,
            ]);
            $params = [
                'wsClientId' => $data['wsClientId'],
                'type' => 'success',
                'message' => "Email to <b>{$receiver->email}</b> sent successfully.",
            ];
            // Log::info($params);
            broadcast(new WssToastrMessageChannel($params));
        }

        // return "OK";
    }
}
