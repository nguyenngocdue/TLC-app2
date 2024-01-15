<?php

namespace App\Listeners;

use App\Events\RequestSignOffEvent;
use App\Events\WssToastrMessageChannel;
use App\Mail\MailRequestSignOff;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestSignOffListener implements ShouldQueue
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

    private function getMeta($data)
    {
        // Log::info($data);
        $tableName = $data['tableName'];
        $signableId = $data['signableId'];

        $modelPath = Str::modelPathFrom($tableName);
        $sheet = $modelPath::find($signableId);
        $chklst = $sheet->getChklst;
        // Log::info($chklst);
        $prodOrder = $chklst->getProdOrder;
        $subProject = $chklst->getSubProject;
        $project = $subProject->getProject;
        // Log::info($prodOrder);

        $result = [
            "projectName" => $project->name,
            "subProjectName" => $subProject->name,
            "moduleName" => $prodOrder->production_name . " (" . $prodOrder->name . ")",
            "disciplineName" => $sheet->getProdDiscipline->name,
            "checksheetName" => $sheet->name,
            'url' => route($tableName . ".edit", $signableId),
        ];
        // Log::info($result);
        return $result;
    }

    private function getUsers($data)
    {
        $requester = User::find($data['requesterId']);
        $receivers = array_map(fn ($uid) => User::find($uid), $data['uids']);
        $category_id = FieldSeeder::getIdFromFieldName($data['category']);
        return [$requester, $receivers, $category_id];
    }

    public function handle(RequestSignOffEvent $event)
    {
        $data = $event->data;
        $signableId = $data['signableId'];
        [$requester, $receivers, $category_id] = $this->getUsers($data);

        foreach ($receivers as $receiver) {
            // Log::info($receiver);
            try {
                $params = ['receiverName' => $receiver->name, 'requesterName' => $requester->name,];
                $params += $this->getMeta($data);
                $mail = new MailRequestSignOff($params);
                $subject = "[ICS/$signableId] - Request Sign Off - " . env("APP_NAME");
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
            broadcast(new WssToastrMessageChannel([
                'wsClientId' => $data['wsClientId'],
                'type' => 'success',
                'message' => "Email to <b>{$receiver->email}</b> sent successfully.",
            ]));
        }

        // return "OK";
    }
}
