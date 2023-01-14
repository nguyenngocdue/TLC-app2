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
        if (!isset($allApps[$singular])) return ['title' => Str::upper("$singular is missing in LibApps")];
        $item = $allApps[$singular];
        return $item;
    }
}
