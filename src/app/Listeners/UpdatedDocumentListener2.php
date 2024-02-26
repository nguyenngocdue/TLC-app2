<?php

namespace App\Listeners;

use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\MailUpdatedDocument;
use App\Models\User;
use App\Utils\SendMaiAndNotification\CheckDefinitionsNew;
use App\Utils\Support\Json\BallInCourts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdatedDocumentListener2 implements ShouldQueue
{
    use CheckDefinitionsNew;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // Log::info("UpdatedDocumentListener constructor");
    }
    private function getIdsByBICUsers(array $obj, array $bic,$modelPath,$id)
    {
        $status = $obj['status'];
        $bic_users = $bic[$status]['ball-in-court-users'] ?? false;
        $isAlsoSendToUsersDiscipline = $bic[$status]['also-send-to-users-discipline'] ?? false;
        if($bic_users){
            $model = $modelPath::find($id);
            $bic_users = str_replace("()","",$bic_users);
            $ids = $model->{$bic_users}()->where('resigned',0)->where('show_on_beta',0)->pluck('id')->toArray();
            if($isAlsoSendToUsersDiscipline){
                $alsoSendToUsersDisciplineIds = [];
                foreach ($ids as $id){
                    $alsoSendToUsersDisciplineIds[] = User::findFromCache($id)->getUserDiscipline->getDefAssignee->id ?? "";
                }
                $ids = array_unique(array_merge($ids,$alsoSendToUsersDisciplineIds));
            }
            return $ids;
        }
        return [];
    }
    private function getValues(array $obj, array $bic, $type)
    {
        $status = $obj['status'];
        $bic_assignee = $bic[$status]['ball-in-court-assignee'] ?: 'owner_id';
        $bic_monitors = $bic[$status]['ball-in-court-monitors'] ?: "getMonitors1()";
        

        if (!isset($obj[$bic_assignee]) || is_null($obj[$bic_assignee])) {
            $msg = $bic_assignee . " is not found in $type (UpdatedDocumentListener2). Halted.";
            Log::error($msg);
            dump($obj);
            dd($msg); //If in QUEUE, this will never show on screen.
        }
        if (!isset($obj[$bic_monitors]) || is_null($obj[$bic_monitors])) {
            $msg = $bic_monitors . " is not found in $type (UpdatedDocumentListener2). Halted.";
            Log::error($msg);
            dump($obj);
            dd($msg); //If in QUEUE, this will never show on screen.
        }

        $bic_id = 1 * $obj[$bic_assignee];
        $monitor_ids = $obj[$bic_monitors];

        $result = [
            'status' => $status,

            'bic_assignee' => $bic_assignee,
            'bic_assignee_uid' => $bic_id,
            'bic_assignee_name' => User::findFromCache($bic_id)->name,

            'bic_monitors' => $bic_monitors,
            'bic_monitors_uids' => array_map(fn ($i) => $i * 1, $monitor_ids),
            'bic_monitors_names' => array_map(fn ($i) => User::findFromCache($i * 1)->name, $monitor_ids),
        ];

        return $result;
    }

    private function getDiff($a, $b)
    {
        return [
            'status' => ($a['status'] != $b['status']),
            'bic_assignee_uid' => ($a['bic_assignee_uid'] != $b['bic_assignee_uid']),
            'bic_monitors_uids' => !Arr::sameContent($a['bic_monitors_uids'], $b['bic_monitors_uids']),
        ];
    }

    private function sendMail($previousValue, $currentValue, $diff, $type, $id , $ids)
    {
        $app = LibApps::getFor($type);
        if ($app['do_not_send_notification_mails'] ?? false) {
            Log::info("UpdatedDocumentListener2: There is an update event fired, but do_not_send_notification_mails so no mail sent.");
            return;
        }
        if (Arr::allElementsAre($diff, false)) {
            Log::info("UpdatedDocumentListener2: There is an update event fired, but no changes so no mail sent.");
            return;
        }

        $nickname = strtoupper($app['nickname']);
        $appTitle = $app['title'];
        $subject = "[$nickname/$id] - $appTitle - " . env("APP_NAME");

        $receiver = User::find($currentValue['bic_assignee_uid']);
        $mail = new MailUpdatedDocument([
            'previousValue' => $previousValue,
            'currentValue' => $currentValue,
            'diff' => $diff,

            'action' => route(Str::plural($type) . '.edit', $id),
            'name' => $receiver->name,
        ]);
        $mail->subject($subject);

        $cc = array_unique([$previousValue['bic_assignee_uid'], ...$previousValue['bic_monitors_uids'],
         ...$currentValue['bic_monitors_uids'],...$ids]);
        $cc = array_map(fn ($i) => User::findFromCache($i), $cc);

        Mail::to($receiver)
            ->cc($cc)
            ->bcc(env('MAIL_ARCHIVE_BCC'))
            ->send($mail);
    }

    public function handle(UpdatedDocumentEvent $event)
    {
        $previousValue = $event->previousValue;
        $currentValue = $event->currentValue;
        $type = $event->type;
        $modelPath = $event->modelPath;
        $id = $currentValue['id'];
        $bic = BallInCourts::getAllOf($type);

        // Log::info($previousValue);
        // Log::info($currentValue);
        $previousValue = $this->getValues($previousValue, $bic, $type);
        $currentValue = $this->getValues($currentValue, $bic, $type);
        $diff = $this->getDiff($previousValue, $currentValue);
        // Log::info(json_encode($previousValue));
        // Log::info(json_encode($currentValue));
        // Log::info(json_encode($diff));

        $this->sendMail($previousValue, $currentValue, $diff, $type, $id,$this->getIdsByBICUsers($currentValue,$bic,$modelPath,$id));
    }
}
