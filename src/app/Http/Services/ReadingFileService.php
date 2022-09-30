<?php

namespace App\Http\Services;


class ReadingFileService
{
    public function indexProps($type)
    {
        $path = storage_path("/json/entities/$type/props.json");
        $props = json_decode(file_get_contents($path), true);
        return $props;
    }
}
