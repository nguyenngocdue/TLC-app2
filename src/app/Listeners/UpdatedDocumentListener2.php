<?php

namespace App\Listeners;

use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\MailChangeStatus2;
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

    private function getValues(array $obj, array $bic, $type)
    {
        $status = $obj['status'];
        $bic_assignee = $bic[$status]['ball-in-court-assignee'] ?: 'owner_id';
        $bic_monitors = $bic[$status]['ball-in-court-monitors'] ?: "getMonitors1()";

        if (!isset($obj[$bic_assignee])) dd($bic_assignee . " is not found in $type (UpdatedDocumentListener2).");
        if (!isset($obj[$bic_monitors])) dd($bic_monitors . " is not found in $type (UpdatedDocumentListener2).");

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

    private function sendMail($previousValue, $currentValue, $diff, $type, $id)
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
        $mail = new MailChangeStatus2([
            'previousValue' => $previousValue,
            'currentValue' => $currentValue,
            'diff' => $diff,

            'action' => route(Str::plural($type) . '.edit', $id),
            'name' => $receiver->name,
        ]);
        $mail->subject($subject);

        $cc = array_unique([$previousValue['bic_assignee_uid'], ...$previousValue['bic_monitors_uids'], ...$currentValue['bic_monitors_uids']]);
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
        $id = $currentValue['id'];
        $bic = BallInCourts::getAllOf($type);

        $previousValue = $this->getValues($previousValue, $bic, $type);
        $currentValue = $this->getValues($currentValue, $bic, $type);
        $diff = $this->getDiff($previousValue, $currentValue);

        // Log::info(json_encode($previousValue));
        // Log::info(json_encode($currentValue));
        // Log::info(json_encode($diff));

        $this->sendMail($previousValue, $currentValue, $diff, $type, $id);
    }
}
