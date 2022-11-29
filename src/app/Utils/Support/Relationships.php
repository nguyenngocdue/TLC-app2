<?php

namespace App\Utils\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Relationships
{
    public static function getAllOf($type)
    {
        $type = Str::plural($type);
        $path = storage_path() . "/json/entities/$type/relationships.json";
        if (!file_exists($path)) {
            Log::error("$path not found");
            return [];
        }
        return json_decode(file_get_contents($path), true);
    }
}
