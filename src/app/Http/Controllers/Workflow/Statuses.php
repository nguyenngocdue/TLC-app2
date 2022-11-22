<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Facades\Storage;

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
        $result = array_filter($allStatuses, fn ($status) => in_array($status, $json), ARRAY_FILTER_USE_KEY);

        return $result;
    }
}
