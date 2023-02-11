<?php

namespace App\Utils\Support\Json;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SuperProps
{
    private static $result = [], $type = '';

    private static function makeRelationshipObject($type)
    {
        $allRelationship = Relationships::getAllOf($type);
        $modelPath = Str::modelPathFrom($type);
        $dummyInstance = new ($modelPath);
        $result = [];
        foreach ($allRelationship as $key => $rls) {
            $column_name = $rls['control_name'];
            $rls['control_name_function'] = substr($key, 1); //remove first "_";

            foreach ($dummyInstance->eloquentParams as $key2 => $params) {
                if ("_" . $key2 === $key) {
                    $rls['eloquentParams'] = $params;
                    $rls['table'] = (new $params[1])->getTable();
                    $rls['type'] = ucfirst(Str::singular($rls['table']));
                    break;
                }
            }
            foreach ($dummyInstance->oracyParams as $key2 => $params) {
                if ("_" . $key2 === $key) {
                    $rls['oracyParams'] = $params;
                    $rls['table'] = (new $params[1])->getTable();
                    $rls['type'] = ucfirst(Str::singular($rls['table']));
                    break;
                }
            }

            $rls['filter_columns'] = Str::parseArray($rls['filter_columns']);
            $rls['filter_values'] = Str::parseArray($rls['filter_values']);

            $result["_" . $column_name] = $rls;
        }
        return $result;
    }

    private static function attachJson($external_name, &$allProps, $externals)
    {
        foreach (array_keys($allProps) as $column_name) $allProps[$column_name][$external_name] = [];
        foreach ($externals as $column_name => $value) {
            // dump($column_name);
            if (isset($allProps[$column_name])) {
                foreach ($value as $k => $v) {
                    if (in_array($k, ['name', 'column_name'])) continue;
                    $allProps[$column_name][$external_name][$k] = $v;
                }
            } else {
                // dump($externals);
                dump("Orphan json attributes found in " . static::$type . "\\" . $external_name . ".json\\" . $column_name . " when constructing SuperProps");
                static::$result['problems']["orphan_$external_name"][] = "Column name not found $column_name - $external_name" . $value['name'];
            }
        }
    }

    private static function makeCheckbox($dataSource)
    {
        $result = [];
        foreach ($dataSource as $key => $value) {
            unset($value['name']);
            unset($value['column_name']);
            $items = [];
            foreach ($value as $k => $v) {
                if ($v === 'true') $items[] = $k;
            }
            $result[$key] = $items;
        }
        return $result;
    }

    private static function makeFromWhiteList($dataSource)
    {
        $result = [];
        foreach ($dataSource as $key => $value) {
            unset($value['name']);
            $items = [];
            foreach ($value as $k => $v) {
                $items[$k] = $v;
            }
            $result[$key] = $items;
        }
        return $result;
    }

    private static function readProps($type)
    {
        $allProps = Props::getAllOf($type);
        // static::attachJson("listeners", $allProps, Listeners::getAllOf($type));
        static::attachJson("default-values", $allProps, DefaultValues::getAllOf($type));
        static::attachJson("relationships", $allProps, static::makeRelationshipObject($type));

        static::attachJson("visible-props", $allProps, static::makeCheckbox(VisibleProps::getAllOf($type)));
        static::attachJson("hidden-props", $allProps, static::makeCheckbox(HiddenProps::getAllOf($type)));
        static::attachJson("required-props", $allProps,  static::makeCheckbox(RequiredProps::getAllOf($type)));
        static::attachJson("read-only-props", $allProps, static::makeCheckbox(ReadOnlyProps::getAllOf($type)));

        static::attachJson("visible-wl-role-sets", $allProps, static::makeCheckbox(VisibleWLProps::getAllOf($type)));
        static::attachJson("hidden-wl-role-sets", $allProps, static::makeCheckbox(HiddenWLProps::getAllOf($type)));
        static::attachJson("required-wl-role-sets", $allProps,  static::makeCheckbox(RequiredWLProps::getAllOf($type)));
        static::attachJson("read-only-wl-role-sets", $allProps, static::makeCheckbox(ReadOnlyWLProps::getAllOf($type)));
        return $allProps;
    }

    private static function readStatuses($type)
    {
        $allStatuses = LibStatuses::getFor($type);
        static::attachJson("transitions", $allStatuses, static::makeCheckbox(Transitions::getAllOf($type)));
        static::attachJson("ball-in-courts", $allStatuses, static::makeFromWhiteList(BallInCourts::getAllOf($type)));
        static::attachJson("action-buttons", $allStatuses, static::makeFromWhiteList(ActionButtons::getAllOf($type)));
        return $allStatuses;
    }

    private static function readIntermediate($type)
    {
        $result = [];
        $data = IntermediateProps::getAllOf($type);
        foreach ($data as $key => $value) {
            unset($value['name']);
            unset($value['column_name']);
            foreach ($value as $k => $v) {
                if ($v) $result[$k][$v][] = $key;
            }
        }
        return $result;
    }

    private static function readSettings($type)
    {
        $result = [];
        static::attachJson("definitions", $result, static::makeCheckbox(Definitions::getAllOf($type)));
        return $result;
    }

    private static function make($type)
    {
        static::$type = $type;
        static::$result['problems'] = [];
        static::$result['type'] = Str::singular($type);
        static::$result['plural'] = Str::plural($type);
        static::$result['props'] = static::readProps($type);
        static::$result['statuses'] = static::readStatuses($type);
        static::$result['intermediate'] = static::readIntermediate($type);
        static::$result['settings'] = static::readSettings($type);
        return static::$result;
    }

    public static function getFor($type)
    {
        if (is_null($type)) dd("Type is missing, SuperProps cant instantiate.");
        if (App::isLocal()) return static::make($type);
        $key = "super_prop_$type";
        if (!Cache::has($key)) {
            Cache::rememberForever($key, fn () => static::make($type));
        }
        return Cache::get($key);
    }

    public static function invalidateCache($type)
    {
        if (App::isLocal()) return;
        $key = "super_prop_$type";
        Cache::forget($key);
    }
}
