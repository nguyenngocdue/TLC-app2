<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LibAppCreations extends AbstractLib
{
    protected static $key = "app-creations";

    public static function getAll()
    {
        $result = parent::getAll();
        // dump($result);
        $allApps = LibApps::getAll();
        // dump($allApps);
        foreach ($result as &$value) $value['row_color'] = 'red';
        foreach ($allApps as $key => $app) {
            $isDisallowed = isset($app['disallowed_direct_creation']) && $app['disallowed_direct_creation'];
            // dump("$key ==== $isDisallowed");
            if ($isDisallowed) {
                if (isset($result[$key])) {
                    unset($result[$key]['row_color']);
                } else {
                    $result[$key] = [
                        'name' => $key,
                        'row_color' => 'green',
                    ];
                }
            }
        }

        return $result;
    }

    public static function getFor($type)
    {
        $all = static::getAll();
        return $all[$type];
    }
}
