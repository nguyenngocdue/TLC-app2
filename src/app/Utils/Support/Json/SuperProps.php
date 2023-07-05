<?php

namespace App\Utils\Support\Json;

use App\Http\Controllers\Workflow\LibProfileFields;
use App\Http\Controllers\Workflow\LibStandardDefaultValues;
use App\Http\Controllers\Workflow\LibStandardProps;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\CacheToRamForThisSection;
use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class SuperProps
{
    private static $result = [], $type = '';

    private static function makeRelationshipObject($type)
    {
        $allRelationship = Relationships::getAllOf($type);
        $modelPath = Str::modelPathFrom($type);
        $dummyInstance = null;
        if ($type !== 'role_set') $dummyInstance = new ($modelPath);
        $result = [];
        foreach ($allRelationship as $key => $rls) {
            $column_name = $rls['control_name'];
            $rls['control_name_function'] = substr($key, 1); //remove first "_";

            if ($dummyInstance) {
                foreach ($modelPath::$eloquentParams as $key2 => $params) {
                    if ("_" . $key2 === $key) {
                        $rls['eloquentParams'] = $params;
                        $rls['table'] = (new $params[1])->getTable();
                        $rls['type'] = ucfirst(Str::singular($rls['table']));
                        break;
                    }
                }
                foreach ($modelPath::$oracyParams as $key2 => $params) {
                    if ("_" . $key2 === $key) {
                        $rls['oracyParams'] = $params;
                        $rls['table'] = (new $params[1])->getTable();
                        $rls['type'] = ucfirst(Str::singular($rls['table']));
                        break;
                    }
                }
            }

            $rls['filter_columns'] = Str::parseArray($rls['filter_columns']);
            $rls['filter_values'] = Str::parseArray($rls['filter_values']);
            $rls['radio_checkbox_colspan'] = $rls['radio_checkbox_colspan'] ? $rls['radio_checkbox_colspan'] : 4;


            $result["_" . $column_name] = $rls;
        }
        return $result;
    }

    private static function attachJson($external_name, &$allProps, $toBeAttached, $standardConfig = [])
    {
        foreach (array_keys($allProps) as $column_name) $allProps[$column_name][$external_name] = [];
        $keys = array_keys($toBeAttached);
        foreach ($standardConfig as $key => $value) {
            if (in_array($key, $keys)) {
                foreach ($value as $k => $v) {
                    if (!is_null($v)) $toBeAttached[$key][$k] = $value[$k];
                }
            }
        }
        foreach ($toBeAttached as $column_name => $value) {
            // dump($column_name);
            if (isset($allProps[$column_name])) {
                foreach ($value as $k => $v) {
                    if (in_array($k, ['name', 'column_name'])) continue;
                    $allProps[$column_name][$external_name][$k] = $v;
                }
            } else {
                // dump($toBeAttached);
                dump("Orphan json attributes found in " . static::$type . "\\" . $external_name . ".json\\" . $column_name . " when constructing SuperProps");
                static::$result['problems']["orphan_$external_name"][] = "Column name not found $column_name - $external_name" . ($value['name'] ?? "Unknown value_name");
            }
        }
        // dump($allProps);
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

    private static function attachProperty($type)
    {
        $allProps = Props::getAllOf($type);
        $result = [];
        foreach ($allProps as $propName => $prop) {
            $control =  $prop['control'];
            if (in_array($control, ['comment', 'attachment'])) {
                $result[$propName] = Properties::getFor($control, $propName);
            }
        }
        // dump($result);
        return $result;
    }
    private static function overrideStandardProfileFields(&$allProps){
        $libProfileFields = LibProfileFields::getAll();
        $viewRender = 'me';
        if($viewRender){
        foreach ($allProps as $propKey => &$value) {
                foreach ($libProfileFields as $field) {
                    if($field['name'] == $propKey) {
                        $value['hidden_edit'] = isset($field[$viewRender .'_hidden']) ? $field[$viewRender .'_hidden'] : '';
                        $value['read_only'] = isset($field[$viewRender .'_readonly']) ? $field[$viewRender .'_readonly'] : '';
                    }
                }
            }
        }
    }

    private static function overrideStandardConfig(&$allProps)
    {
        // $standardConfig = config("standardProps");
        // dump($standardConfig);
        $standardConfig = LibStandardProps::getAll();
        //
        foreach ($standardConfig as $propKey => $value) {
            $dummy = [
                "hidden_view_all" => '',
                "hidden_edit" => '',
                "hidden_label" => '',
                "hidden_filter" => '',
                "hidden_print" => '',
                "read_only" => '',
                "save_on_change" => '',
                "duplicatable" => '',
                "new_line" => '',

                "width" => 100,
                "col_span" => 12,
                "control" => 'unknown',
                "column_type" => 'unknown',
                "column_name" => 'unknown',
                "label" => 'unknown',
            ];
            foreach ($value as $k => $v) $dummy[$k] = $v;
            $allProps[$propKey] = $dummy;
        }
        // dd($allProps);
    }

    private static function readProps($type,$viewRender)
    {
        $allProps = Props::getAllOf($type);
        static::overrideStandardConfig($allProps);
        if($viewRender)static::overrideStandardProfileFields($allProps);
        // dump($allProps);
        foreach ($allProps as &$prop) {
            $prop['width'] = $prop['width'] ? $prop['width'] : 100;
            $prop['col_span'] = $prop['col_span'] ? $prop['col_span'] : 12;
            if (in_array(substr($prop['name'], 1), JsonControls::getIgnoreDuplicatable())) {
                $prop['duplicatable'] = '';
            } else {
                $prop['duplicatable'] = $prop['duplicatable'] ?? ''; //<<CONFIG_MIGRATE
            }
        }
        // static::attachJson("listeners", $allProps, Listeners::getAllOf($type));
        $standardDefaultValues = LibStandardDefaultValues::getAll();
        static::attachJson("default-values", $allProps, DefaultValues::getAllOf($type), $standardDefaultValues);
        // static::attachJson("realtimes", $allProps, Realtimes::getAllOf($type));
        static::attachJson("relationships", $allProps, static::makeRelationshipObject($type));
        static::attachJson("properties", $allProps, static::attachProperty($type));

        return $allProps;
    }

    private static function loadCapa($type)
    {
        $capa = Capabilities::getAllOf($type);
        $statuses = LibStatuses::getFor($type);
        $result = [];
        $statusesKeys = array_keys($statuses);
        foreach ($statusesKeys as $statusKey) {
            $result[$statusKey][] = 'admin';
        }
        foreach ($capa as $roleSet => $value) {
            foreach ($statusesKeys as $statusKey) {
                if (isset($value[$statusKey]) && $value[$statusKey] == 'true') {
                    $result[$statusKey][] = $roleSet;
                }
            }
        }
        foreach ($statusesKeys as $statusKey) {
            $result[$statusKey] = array_unique($result[$statusKey]);
        }
        return $result;
    }

    private static function readStatuses($type)
    {
        $allStatuses = LibStatuses::getFor($type);
        static::attachJson("transitions", $allStatuses, static::makeCheckbox(Transitions::getAllOf($type)));
        static::attachJson("ball-in-courts", $allStatuses, static::makeFromWhiteList(BallInCourts::getAllOf($type)));
        static::attachJson("action-buttons", $allStatuses, static::makeFromWhiteList(ActionButtons::getAllOf($type)));
        static::attachJson("capability-roles", $allStatuses, static::loadCapa($type));
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
        $result["definitions"] = static::makeCheckbox(Definitions::getAllOf($type));
        return $result;
    }

    private static function getTablesFromProps($props)
    {
        $result = [];
        foreach ($props as $prop) {
            if (isset($prop['relationships']['table'])) {
                if ('relationship_renderer' == $prop['control']) {
                    $result[$prop['relationships']['table']][] = $prop['column_name'];
                }
            }
        }
        return $result;
    }

    private static function getCommentsFromProps($props)
    {
        $result = [];
        $index = 1;
        foreach ($props as $key => $prop) {
            if ($prop['control'] === 'comment')
                $result["comment" . $index++] = $key;
        }
        return $result;
    }

    private static function getAttachmentsFromProps($props)
    {
        $result = [];
        foreach ($props as $key => $prop) {
            if ($prop['control'] == 'attachment') {
                $result[] = $key;
            }
        }
        return $result;
    }

    private static function make($type,$viewRender = null)
    {
        static::$type = $type;
        static::$result['problems'] = [];
        static::$result['type'] = Str::singular($type);
        static::$result['plural'] = Str::plural($type);
        static::$result['props'] = static::readProps($type,$viewRender);
        static::$result['statuses'] = static::readStatuses($type);
        static::$result['intermediate'] = static::readIntermediate($type);
        static::$result['settings'] = static::readSettings($type);
        static::$result['tables'] = static::getTablesFromProps(static::$result['props']);
        static::$result['comments'] = static::getCommentsFromProps(static::$result['props']);
        static::$result['attachments'] = static::getAttachmentsFromProps(static::$result['props']);
        return static::$result;
    }

    public static function getFor($type,$viewRender = null)
    {
        if (is_null($type)) {
            dump("Type is missing, SuperProps cant instantiate.");
            dd();
        }
        $type = Str::singular($type);
        $key = "super_prop_$type";
        $result = CacheToRamForThisSection::get($key, fn () => static::make($type));
        if($viewRender) $result = static::make($type,$viewRender);
        return $result;
    }
    public static function invalidateCache($type)
    {
        if (App::isLocal()) return;
        $type = Str::singular($type);
        $key = "super_prop_$type";
        CacheToRamForThisSection::forget($key);
    }
}
