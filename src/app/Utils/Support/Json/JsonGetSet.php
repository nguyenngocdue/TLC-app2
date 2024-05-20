<?php

namespace App\Utils\Support\Json;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JsonGetSet
{
    protected static $filename = "";
    public static function convertHttpObjectToJson($data, $columns)
    {
        if (!isset($data['name'])) return [];
        foreach ($data['name'] as $key => $name) {
            $array = [];
            foreach ($columns as $column) {
                $value = $data[$column['dataIndex']][$key] ?? "";
                $array[$column['dataIndex']] = $value;
            }
            $result[$name] = $array;
        }
        return $result;
    }

    public static function getAllOf($type)
    {
        $filename = static::$filename;
        $type = Str::plural($type);
        $path = storage_path() . "/json/entities/$type/$filename";
        if (!file_exists($path)) {
            // Log::error("$path not found");
            return [];
        } else {
            // Log::info("Loading $path");
        }
        return json_decode(file_get_contents($path), true);
    }

    public static function setAllOf($type, $data)
    {
        $filename = static::$filename;
        try {
            $type = Str::plural($type);
            $path = "entities/$type/$filename";
            $output = Storage::disk('json')->put($path, json_encode($data, JSON_PRETTY_PRINT), 'public');
            if ($output) {
                toastr()->success("$filename saved successfully!", 'Successfully');
                return true;
            } else {
                toastr()->warning("$filename saved failed. Maybe Permission is missing!", 'Failed');
                // dump(error_get_last()); //<<Useless
                return false;
            }
        } catch (\Throwable $th) {
            toastr()->warning($th, 'Save file json');
        }
    }

    public static function move(&$json, $direction, $key)
    {
        $json = array_values($json);
        for ($index = 0; $index < sizeof($json); $index++) if ($json[$index]['name'] === $key) break;
        $json = Arr::moveDirection($json, $direction, $index, $key);
        $json = Arr::keyBy($json, "name");
    }

    public static function moveTo(&$json, $newIndex, $key)
    {
        // dump($newIndex . " - " . $key);
        $json = array_values($json);
        // dump($json);
        for ($index = 0; $index < sizeof($json); $index++) if ($json[$index]['name'] === $key) break;
        $json = Arr::moveTo($json, $index, $newIndex);
        // dump($json);
        $json = Arr::keyBy($json, "name");
    }
}
