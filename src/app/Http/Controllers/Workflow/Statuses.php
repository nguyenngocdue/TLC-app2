<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Statuses
{
    public static function getAll()
    {
        $path = "workflow/statuses.json";
        $pathFrom = storage_path('json/' . $path);
        $json = json_decode(file_get_contents($pathFrom, true), true);
        return $json;
    }

    public static function setAll($dataSource)
    {
        $path = "workflow/statuses.json";
        // Log::info($dataSource);
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Storage::disk('json')->put($path, $str);
    }

    public static function getFor($entityType)
    {
        $path = "entities/$entityType/statuses.json";
        $pathFrom = storage_path('json/' . $path);
        if (!file_exists($pathFrom)) return [];
        $json = json_decode(file_get_contents($pathFrom, true), true);

        $allStatuses = self::getAll();
        //<< When filter, the order is the order of the allStatus, not the form order
        // $result = array_filter($allStatuses, fn ($status) => in_array($status, $json), ARRAY_FILTER_USE_KEY);
        $result = [];
        foreach ($json as $status) $result[$status] = $allStatuses[$status];

        return $result;
    }

    private static function setFor($entityType, $dataSource)
    {
        $path = "entities/$entityType/statuses.json";
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        return Storage::disk('json')->put($path, $str);
    }

    public static function move($direction, $entityType, $name)
    {
        // dd($direction, $entityType, $name);
        $entityType = Str::plural($entityType);
        $path = "entities/$entityType/statuses.json";
        $pathFrom = storage_path('json/' . $path);
        $json = (file_exists($pathFrom)) ? json_decode(file_get_contents($pathFrom, true), true) : [];

        $index = array_search($name, $json);
        // dump($direction, $name, $index, $json);
        switch ($direction) {
            case "up":
                if ($index === 0) {
                    $value = $json[0];
                    unset($json[0]);
                    array_push($json, $value);
                } else {
                    $tmp = $json[$index - 1];
                    $json[$index - 1] = $json[$index];
                    $json[$index] = $tmp;
                }
                break;
            case "down":
                if ($index === sizeof($json) - 1) {
                    $value = array_pop($json);
                    array_unshift($json, $value);
                } else {
                    $tmp = $json[$index + 1];
                    $json[$index + 1] = $json[$index];
                    $json[$index] = $tmp;
                }
                break;
            case "left":
                array_push($json, $name);
                break;
            case "right":
                $json = array_filter($json, fn ($name0) => $name !== $name0);
                break;
        }
        // dd($direction, $json);
        return self::setFor($entityType, array_values($json));
    }
}
