<?php

namespace App\Listeners;

use App\Events\SendMailForInspector;
use App\Http\Controllers\Workflow\LibApps;
use App\Mail\SendMailInspector;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailForInspectorListener
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

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendMailForInspector  $event
     * @return void
     */
    public function handle(SendMailForInspector $event)
    {
        try {
            $id = $event->{'id'};
            $user = User::findFromCache($id);
            $fields = $event->{'fields'};
            Mail::to($user)
                ->bcc($this->getMailBcc())
                ->send(new SendMailInspector([
                    'name' => $user['name'],
                    'subject' => $this->getSubjectMail($fields['type'], $fields['id']),
                    'action' => url($fields['href']),
                ]));
            Toastr::success('Send Mail For Inspector', 'Send Mail For Inspector Successfully');
        } catch (\Throwable $th) {
            Toastr::warning('Send Mail For Inspector',  $th); //'Send Mail For Inspector Failed');
        }
    }
    private function getSubjectMail($type, $id)
    {
        $libApps = LibApps::getFor($type);
        $nickNameEntity = strtoupper($libApps['nickname'] ?? $type);
        $titleEntity = $libApps['title'];
        $subjectMail = '[' . $nickNameEntity . '/' . $id . '] ' .  ' - ' . $titleEntity . ' - ' . config("company.name") . ' APP';
        return $subjectMail;
    }
    private function getMailBcc()
    {
        return env('MAIL_ARCHIVE_BCC', 'info@gmail.com');
    }
}
