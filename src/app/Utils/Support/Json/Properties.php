<?php

namespace App\Utils\Support\Json;

class Properties extends JsonGetSet
{
    protected static $filename = "properties.json";

    // private static function setDefault(&$array, $key, $defaultValue)
    // {
    //     $array[$key] = $array[$key] ?? "";
    //     $array[$key] = $array[$key] ? $array[$key] : $defaultValue;
    // }

    // public static function getAllOf($type)
    // {
    //     $json = parent::getAllOf($type);
    //     // if ($type == 'attachment') {
    //     //     foreach ($json as &$value) {
    //     //         static::setDefault($value, 'max_file_count', 10);
    //     //         static::setDefault($value, 'max_file_size', 10);
    //     //         static::setDefault($value, 'allowed_file_types', 'only_images');
    //     //         // $value['max_file_count'] = $value['max_file_count'] ?? "";
    //     //         // $value['max_file_count'] = $value['max_file_count'] ? $value['max_file_count'] : 10;

    //     //         // $value['max_file_size'] = $value['max_file_size'] ?? "";
    //     //         // $value['max_file_size'] = $value['max_file_size'] ? $value['max_file_size'] : 10;

    //     //         // $value['allowed_file_types'] = $value['allowed_file_types'] ?? "";
    //     //         // $value['allowed_file_types'] = $value['allowed_file_types'] ? $value['allowed_file_types'] : 'only_images';
    //     //     }
    //     // }
    //     // if ($type == 'comment') {
    //     //     foreach ($json as &$value) {
    //     //         static::setDefault($value, 'allowed_to_delete', false);
    //     //         static::setDefault($value, 'force_comment_once', false);
    //     //     }
    //     // }
    //     return $json;
    // }

    public static function getFor($type, $category)
    {
        $all = static::getAllOf($type);
        if (!isset($all['_' . $category])) {
            switch ($type) {
                case 'comment':
                    $property = [
                        'allowed_to_delete' => false,
                        // 'force_comment_once' => false,
                    ];
                    break;

                case 'attachment':
                    $property = [
                        'max_file_count' => 10,
                        'max_file_size' => 10,
                        'allowed_file_types' => 'only_images',
                    ];
                    break;
            }
        } else {
            $property = $all['_' . $category];
        }
        return $property;
    }
}
