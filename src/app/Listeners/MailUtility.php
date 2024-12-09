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

        $subProject = (method_exists($item, 'getSubProject')) ? $item->getSubProject : null;
        $project = (method_exists($item, 'getProject')) ? $item->getProject : null;
        $wirDescription = (method_exists($item, 'getWirDescription')) ? $item->getWirDescription : null;
        $prodOrder = (method_exists($item, 'getProdOrder')) ? $item->getProdOrder : null;

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

        $result = [
            'subject' => $subject,
        ];

        $array = [];
        if ($project) $array[] = $project->name;
        if ($subProject) $array[] = $subProject->name;

        if (sizeof($array) > 0) $result['Project'] = join(" / ", $array);

        if ($wirDescription) $result['WIR Description'] = $wirDescription->name;
        if ($prodOrder) $result['Production Order'] = $prodOrder->name . " (" . $prodOrder->compliance_name . ")";
        if (isset($item->name)) $result['Document Name'] = $item->name;

        return $result;
    }
}
