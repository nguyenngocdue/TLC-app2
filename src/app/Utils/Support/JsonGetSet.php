<?php

namespace App\Utils\Support;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JsonGetSet
{
    public static function getAllOf($type, $filename)
    {
        $type = Str::plural($type);
        $path = storage_path() . "/json/entities/$type/$filename";
        if (!file_exists($path)) {
            Log::error("$path not found");
            return [];
        }
        return json_decode(file_get_contents($path), true);
    }

    public static function setAllOf($type, $data, $filename)
    {
        $type = Str::plural($type);
        $path = "entities/$type/$filename";
        $output = Storage::disk('json')->put($path, json_encode($data, JSON_PRETTY_PRINT), 'public');
        if ($output) {
            Toastr::success("$filename saved successfully!", 'Successfully');
            return true;
        } else {
            Toastr::warning("$filename saved failed. Maybe Permission is missing!", 'Failed');
            return false;
        }
    }
}
