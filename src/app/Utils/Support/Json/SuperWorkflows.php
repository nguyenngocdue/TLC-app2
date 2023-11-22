<?php

namespace App\Utils\Support\Json;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\CacheToRamForThisSection;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SuperWorkflows
{
    private static $result = [];
    private static $adminIsRampage  = !true;

    private static function makeDataFromCheckbox($dataSource)
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

    private static function consolidate($props, $roleSet, $statuses, $name, $list, $whiteList, $defaultValue)
    {
        $result = [];
        $s0 = sizeof($list);
        $s1 = sizeof($whiteList);
        if ($s0 != $s1) {
            if (!empty($whiteList)) {
                dump("Warning: Number of props in $name ($s0) and $name WhiteList ($s1) are different");
            }
        }
        foreach (array_keys($props) as $propName) {
            $propValue = $list[$propName] ?? [];
            $thisRoleSetIsInWL = false;
            if (isset($whiteList[$propName])) {
                $thisRoleSetIsInWL = in_array($roleSet, $whiteList[$propName]);
            }
            if (static::$adminIsRampage && $roleSet == 'admin') $thisRoleSetIsInWL = true;
            foreach (array_keys($statuses) as $status) {
                if ($thisRoleSetIsInWL) {
                    $value = $defaultValue;
                } else {
                    $value = (isset($propValue[$status]) && $propValue[$status] === 'true');
                }
                $result[$propName][$status] = $value;
            }
        }
        return $result;
    }

    private static function getPropValueByStatus($status, $props)
    {
        $result = [];

        foreach ($props as $propName => $propValue) {
            if ($propValue[$status]) $result[] = $propName;
        }
        return $result;
    }

    private static function readWorkflow($type, $roleSet)
    {
        $statuses = LibStatuses::getFor($type);
        $result = $statuses;
        $capabilities = static::makeDataFromCheckbox(Capabilities::getAllOf($type));
        if (isset($capabilities[$roleSet])) {
            foreach ($capabilities[$roleSet] as $statusKey) {
                $result[$statusKey]['capabilities'] = true;
            }
        }
        foreach (array_keys($statuses) as $statusKey) {
            if (!isset($result[$statusKey]['capabilities'])) {
                $value = static::$adminIsRampage;
                $result[$statusKey]['capabilities'] = $value;
            }
        }

        $props = Props::getAllOf($type);
        $visibleProps = static::consolidate($props, $roleSet, $statuses, "VisibleProps", VisibleProps::getAllOf($type), static::makeDataFromCheckbox(VisibleWLProps::getAllOf($type)), true);
        $readonlyProps = static::consolidate($props, $roleSet, $statuses, "ReadOnlyProps", ReadOnlyProps::getAllOf($type), static::makeDataFromCheckbox(ReadOnlyWLProps::getAllOf($type)), false);
        $hiddenProps = static::consolidate($props, $roleSet, $statuses, "HiddenProps", HiddenProps::getAllOf($type), static::makeDataFromCheckbox(HiddenWLProps::getAllOf($type)), false);
        $requiredProps = static::consolidate($props, $roleSet, $statuses, "RequiredProps", RequiredProps::getAllOf($type), static::makeDataFromCheckbox(RequiredWLProps::getAllOf($type)), false);

        foreach (array_keys($statuses) as $statusKey) {
            $result[$statusKey]['visible']  = static::getPropValueByStatus($statusKey, $visibleProps);
            $result[$statusKey]['readonly']  = static::getPropValueByStatus($statusKey, $readonlyProps);
            $result[$statusKey]['hidden']  = static::getPropValueByStatus($statusKey, $hiddenProps);
            $result[$statusKey]['required']  = static::getPropValueByStatus($statusKey, $requiredProps);
        }

        return $result;
    }

    private static function make($type, $roleSet)
    {
        // static::$type = $type;
        static::$result['problems'] = [];
        static::$result['type'] = Str::singular($type);
        static::$result['plural'] = Str::plural($type);
        static::$result['roleSet'] = $roleSet;
        static::$result['workflows'] = static::readWorkflow($type, $roleSet);
        return static::$result;
    }

    public static function getFor($type, $roleSet = null)
    {
        if (is_null($roleSet)) $roleSet = CurrentUser::getRoleSet();
        if (is_null($type) || is_null($roleSet)) dd("Type or Role_set is missing, SuperWorkflow cant instantiate.");
        $type = Str::singular($type, $roleSet);
        $key = "super_workflow_{$type}";
        $result = CacheToRamForThisSection::get($key, fn () => static::make($type, $roleSet), $roleSet);
        // dump($result);
        return $result;
    }

    public static function invalidateCache($type)
    {
        if (App::isLocal()) return;
        $type = Str::singular($type);
        $key = "super_workflow_{$type}"; //<< Admin set the setting, but apply for all users
        // Cache::forget($key);
        CacheToRamForThisSection::forget($key, 'roleSet(to trigger tag mode)');
    }

    public static function isAllowed($status, $type)
    {
        if ($status === '' || $status === null) return false;
        $currentRoleSet = CurrentUser::getRoleSet();
        $sw = static::getFor($type, $currentRoleSet);
        if (!isset($sw['workflows'][$status])) {
            dump("Orphan status [{$status}] detected, please fix this or you will not be able to edit this document.");
            return false;
        } else {
            return $sw['workflows'][$status]['capabilities'];
        }
    }
}
