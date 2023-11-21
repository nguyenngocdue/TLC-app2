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

class UpdatedDocumentListener2 //implements ShouldQueue
{
    use CheckDefinitionsNew;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // Log::info("UpdatedDocumentListener");
    }

    private function getValues(array $obj, array $bic)
    {
        $status = $obj['status'];
        $bic_assignee = $bic[$status]['ball-in-court-assignee'];
        $bic_monitors = $bic[$status]['ball-in-court-monitors'];

        $result = [
            'status' => $status,
            'bic_assignee' => $bic_assignee,
            'bic_monitors' => $bic_monitors,
            'bic_assignee_uid' => $obj[$bic_assignee] * 1,
            'bic_monitors_uids' => array_map(fn ($i) => $i * 1, $obj[$bic_monitors]),
            'bic_assignee_name' => User::findFromCache($obj[$bic_assignee])->name,
            'bic_monitors_names' => array_map(fn ($i) => User::findFromCache($i * 1)->name, $obj[$bic_monitors]),
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
        if ($app['do_not_send_notification_mails'] ?? false) return;

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

        $previousValue = $this->getValues($previousValue, $bic);
        $currentValue = $this->getValues($currentValue, $bic);
        $diff = $this->getDiff($previousValue, $currentValue);

        // Log::info(json_encode($previousValue));
        // Log::info(json_encode($currentValue));
        // Log::info(json_encode($diff));

        $this->sendMail($previousValue, $currentValue, $diff, $type, $id);
    }
}
