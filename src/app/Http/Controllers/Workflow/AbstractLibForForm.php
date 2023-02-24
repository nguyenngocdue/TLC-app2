<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\CacheToRamForThisSection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbstractLibForForm extends AbstractLib
{
    protected static $key = "abstract_lib_for_form";

    private static function _getForExpensive($entityType)
    {
        $plural = Str::plural($entityType);
        $result = [];
        $path = "entities/$plural/" . static::$key . ".json";
        $pathFrom = storage_path('json/' . $path);
        if (!file_exists($pathFrom)) $result = [];
        else $result = json_decode(file_get_contents($pathFrom, true), true);
        return $result;
    }

    private static function _getFor($entityType)
    {
        $key = static::$key . "_of_$entityType";
        return CacheToRamForThisSection::get($key, $entityType, fn ($a) => static::_getForExpensive($a));
    }

    private static function setFor($entityType, $dataSource)
    {
        $singular = Str::singular($entityType);
        $plural = Str::plural($entityType);
        $path = "entities/$plural/" . static::$key . ".json";
        $str = json_encode($dataSource, JSON_PRETTY_PRINT);
        Cache::forget(static::$key . "_of_$singular");
        return Storage::disk('json')->put($path, $str);
    }

    public static function getFor($entityType)
    {
        $json = static::_getFor($entityType);

        $allStatuses = static::getAll();
        //<< When filter, the order is the order of the allStatus, not the form order
        // $result = array_filter($allStatuses, fn ($status) => in_array($status, $json), ARRAY_FILTER_USE_KEY);
        $result = [];
        foreach ($json as $status) {
            if (isset($allStatuses[$status])) {
                $result[$status] = $allStatuses[$status];
            } else {
                echo "Found an orphan status, please remove it in statuses.json of $entityType manually";
                dump($status);
            }
        }

        return $result;
    }

    public static function move($direction, $entityType, $name)
    {
        $entityType = Str::plural($entityType);
        $json = static::_getFor($entityType);

        $index = array_search($name, $json);
        $json = Arr::moveDirection($json, $direction, $index, $name);
        return static::setFor($entityType, array_values($json));
    }
}
