<?php

namespace App\Listeners;

use App\Events\RecallSignOffEvent;
use App\Events\WssToastrMessageChannel;
use App\Mail\MailSignOffRecall;
use App\Models\Signature;
use App\Models\User;
use Database\Seeders\FieldSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecallSignOffListener implements ShouldQueue
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

    public function handle(RecallSignOffEvent $event)
    {
        $data = $event->data;
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
                $params += $this->getMeta($data);
                $mail = new MailSignOffRecall($params);
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
