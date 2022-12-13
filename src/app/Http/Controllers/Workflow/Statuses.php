<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Statuses
{
    private static $statuses_path = "workflow/statuses.json";
    public static function getAll()
    {
        if (!Cache::has('statuses_of_the_app')) {
            $pathFrom = storage_path('json/' . self::$statuses_path);
            $json = json_decode(file_get_contents($pathFrom, true), true);
            Cache::rememberForever('statuses_of_the_app', fn () => $json);
        }
        return Cache::get('statuses_of_the_app');
    }

    public static function setAll($dataSource)
    {
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Cache::forget('statuses_of_the_app');
        return Storage::disk('json')->put(self::$statuses_path, $str);
    }

    private static function _getFor($entityType)
    {
        $singular = Str::singular($entityType);
        $plural = Str::plural($entityType);
        $cacheKey = "statuses_of_$singular";
        $result = [];
        if (!Cache::has($cacheKey)) {
            $path = "entities/$plural/statuses.json";
            $pathFrom = storage_path('json/' . $path);
            if (!file_exists($pathFrom)) $result = [];
            else $result = json_decode(file_get_contents($pathFrom, true), true);
            Cache::rememberForever($cacheKey, fn () => $result);
        }
        return Cache::get($cacheKey);
    }

    private static function setFor($entityType, $dataSource)
    {
        $singular = Str::singular($entityType);
        $plural = Str::plural($entityType);
        $path = "entities/$plural/statuses.json";
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Cache::forget("statuses_of_$singular");
        return Storage::disk('json')->put($path, $str);
    }

    public static function getFor($entityType)
    {
        $json = self::_getFor($entityType);

        $allStatuses = self::getAll();
        //<< When filter, the order is the order of the allStatus, not the form order
        // $result = array_filter($allStatuses, fn ($status) => in_array($status, $json), ARRAY_FILTER_USE_KEY);
        $result = [];
        foreach ($json as $status) $result[$status] = $allStatuses[$status];

        return $result;
    }

    public static function move($direction, $entityType, $name)
    {
        $entityType = Str::plural($entityType);
        $json = self::_getFor($entityType);

        $index = array_search($name, $json);
        $json = Arr::moveDirection($json, $direction, $index, $name);
        return self::setFor($entityType, array_values($json));
    }
}
