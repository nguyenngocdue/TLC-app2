<?php

namespace App\Utils\Support\Json;

use App\Http\Controllers\Workflow\LibStatuses;

class BallInCourts extends JsonGetSet
{
    protected static $filename = "ball-in-courts.json";

    public static function getAllOf($type)
    {
        $dataSource = parent::getAllOf($type);
        $allStatuses = LibStatuses::getFor($type);
        foreach ($allStatuses as $status1) {
            $name = $status1['name'];
            $dataSource[$name]['name'] = $name;
        }
        foreach ($dataSource as &$item) {
            if (!isset($item['ball-in-court-assignee'])) {
                $item['ball-in-court-assignee'] = 'owner_id';
            }
            if (!isset($item['ball-in-court-monitors'])) {
                $item['ball-in-court-monitors'] = 'getMonitors1';
            }
        }
        // dump($dataSource);
        return $dataSource;
    }
}
