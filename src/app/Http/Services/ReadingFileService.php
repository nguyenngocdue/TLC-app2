<?php

namespace App\Http\Services;


class ReadingFileService
{
    public function indexProps($type, $path)
    {
        $storage_path = storage_path("json/master/$type/$path");
        $props = json_decode(file_get_contents($storage_path), true);
        return $props;
    }
}
