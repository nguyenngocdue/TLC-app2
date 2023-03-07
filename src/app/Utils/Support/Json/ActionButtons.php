<?php

namespace App\Utils\Support\Json;

use App\Http\Controllers\Workflow\LibStatuses;

class ActionButtons extends JsonGetSet
{
    protected static $filename = "action_buttons.json";

    public static function getAllOf($type)
    {
        $json = parent::getAllOf($type);
        $statuses = LibStatuses::getFor($type);
        $result = [];
        foreach ($statuses as $statusKey => $status) {
            if (isset($json[$statusKey])) {
                $result[$statusKey] = $json[$statusKey];
                if (!$json[$statusKey]['label']) {
                    $result[$statusKey] = [
                        'label' => $status['title'],
                        'tooltip' => '',
                    ];
                }
            } else {
                $result[$statusKey] = [
                    'label' => $status['title'],
                    'tooltip' => '',
                ];
            }
            $result[$statusKey]['name'] = $statusKey;
        }
        return $result;
    }
}
