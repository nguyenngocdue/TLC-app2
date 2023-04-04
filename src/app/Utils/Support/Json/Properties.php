<?php

namespace App\Utils\Support\Json;

class Properties extends JsonGetSet
{
    protected static $filename = "properties.json";

    public static function getAllOf($type)
    {
        $json = parent::getAllOf($type);
        if ($type == 'attachment') {
            foreach ($json as &$value) {
                $value['max_file_count'] = $value['max_file_count'] ?? "";
                $value['max_file_count'] = $value['max_file_count'] ? $value['max_file_count'] : 10;

                $value['max_file_size'] = $value['max_file_size'] ?? "";
                $value['max_file_size'] = $value['max_file_size'] ? $value['max_file_size'] : 10;

                $value['allowed_file_types'] = $value['allowed_file_types'] ?? "";
                $value['allowed_file_types'] = $value['allowed_file_types'] ? $value['allowed_file_types'] : 'only_images';
            }
        }
        return $json;
    }
}
