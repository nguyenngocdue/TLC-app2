<?php

namespace App\Listeners;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MailUtility
{
    public static function getMailTitle($type, $id, $mailTitle = null)
    {
        $app = LibApps::getFor($type);
        if ($app['do_not_send_notification_mails'] ?? false) return;

        $nickname = strtoupper($app['nickname'] ?: $app['name']);
        $appTitle = $app['title'];
        $subject = "[$nickname/$id]";
        $subject .= " - $appTitle";

        $modelPath = Str::modelPathFrom(Str::plural($type));
        $item = $modelPath::find($id);

        if (method_exists($item, 'getSubProject')) {
            $subProject = $item->getSubProject;
            $subject .= " - (" . $subProject->name . ")";
        } else {
            if (method_exists($item, 'getProject')) {
                $project = $item->getProject;
                $subject .= " - (" . $project->name . ")";
            }
        }
        if ($mailTitle) $subject .= " - $mailTitle";

        $subject .= " - " . env("APP_NAME");

        return $subject;
    }
}
