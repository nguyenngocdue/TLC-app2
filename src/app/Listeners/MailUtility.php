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

        if (method_exists($item, 'getSubProject')) $subProject = $item->getSubProject;
        if (method_exists($item, 'getProject')) $project = $item->getProject;
        if (method_exists($item, 'getWirDescription')) $wirDescription = $item->getWirDescription;

        switch (true) {
            case $subProject:
                $subject .= " - (" . $subProject->name . ")";
                break;
            case $project:
                $subject .= " - (" . $project->name . ")";
                break;
                // case $wirDescription:
                //     $subject .= " - (" . $wirDescription->name . ")";
                //     break;
        }
        if ($mailTitle) $subject .= " - $mailTitle";

        $subject .= " - " . env("APP_NAME");

        return [
            'subject' => $subject,

            'Project' => $project ? $project->name : null,
            'Sub Project' => $subProject ? $subProject->name : null,
            'WIR Description' => $wirDescription ? $wirDescription->name : null,
        ];
    }
}
