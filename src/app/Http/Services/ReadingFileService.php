<?php


namespace App\Http\Services;

use Illuminate\Support\Str;


class ReadingFileService
{
    public function getPath($path)
    {
        $storage_path = storage_path($path);
        // dd($storage_path);
        $props = json_decode(file_get_contents($storage_path), true);
        return $props;
    }
    public function type_getPath($disk, $branch, $type, $nameFile)
    {
        $_type = Str::plural($type);
        $storage_path = storage_path("$disk/$branch/$_type/$nameFile");
        $props = json_decode(file_get_contents($storage_path), true);
        return $props;
    }
}
