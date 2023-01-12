<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Str;

class LibApps extends AbstractLib
{
    protected static $key = "apps";

    public static function getFor($entityType)
    {
        $allApps = static::getAll();
        $singular = Str::singular($entityType);
        $item = $allApps[$singular];
        // $item['title'] = Str::appTitle($item['title']);
        return $item;
    }
}
