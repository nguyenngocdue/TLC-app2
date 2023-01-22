<?php

namespace App\Utils\Support\Json;

use App\BigThink\ModelExtended;
use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SuperProps
{
    private static $result = [];
    private static function makeRelationshipObject($type)
    {
        $allRelationship = Relationships::getAllOf($type);
        // dump("RELATIONSHIP");
        // dump($allRelationship);
        /** @var ModelExtended $instance */
        $instance = new ("App\\Models\\$type");
        $eloquentParams = $instance->eloquentParams;
        $result = [];

        foreach ($allRelationship as $key => $rls) {
            switch ($rls['relationship']) {
                case 'belongsTo': //"getWorkplaces" => ['belongsTo', Workplace::class, 'workplace'],
                    $eloquentKey = substr($key, 1);
                    if (isset($eloquentParams[$eloquentKey][2])) {
                        $column_name = $eloquentParams[$eloquentKey][2];
                        $result["_" . $column_name] = $rls;
                    } else {
                        static::$result['problems']['orphan_relationships'][] = "Key $eloquentKey not found in EloquentParams";
                    }
                    break;
                case 'hasMany': //"posts" => ['hasMany', Post::class, 'owner_id', 'id'],
                case 'belongsToMany': //"prodRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
                case 'hasManyThrough': // "prodSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],
                case "morphTo": //"attachable" => ['morphTo', Attachment::class, 'object_type', 'object_id'],
                case "morphOne": //"avatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
                case "morphMany": //"comment_by_clinic" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
                case 'getCheckedByField': // "mainAffectedPart()" => ["getCheckedByField", Term::class],
                    $column_name = substr($key, 1);
                    $result["_" . $column_name] = $rls;
                    break;
                    // case 'hasOne':
                default:
                    static::$result['problems']['unknown_relationships'][] = $rls['relationship'] . " - " . $rls['name'];
                    break;
            }
        }
        return $result;
    }

    private static function attachJson($external_name, &$allProps, $externals)
    {
        foreach ($externals as $column_name => $value) {
            // dump($column_name);
            $allProps[$column_name][$external_name] = [];
            if (isset($allProps[$column_name])) {
                foreach ($value as $k => $v) $allProps[$column_name][$external_name][$k] = $v;
            } else {
                static::$result['problems']["orphan_$external_name"][] = "Column name not found $column_name - " . $value['name'];
            }
        }
    }

    private static function makeCheckbox($dataSource)
    {
        // unset($dataSource['name']);
        // unset($dataSource['column_name']);
        $result = [];
        // dump($dataSource);
        foreach ($dataSource as $key => $value) {
            unset($value['name']);
            unset($value['column_name']);
            $items = [];
            foreach ($value as $k => $v) {
                if ($v === 'true') $items[] = $k;
            }
            $result[$key] = $items;
        }
        // dump($result);
        return $result;
    }

    private static function makeFromWhiteList($dataSource)
    {
        // dump($dataSource);
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
        static::attachJson("listeners", $allProps, Listeners::getAllOf($type));
        static::attachJson("default-values", $allProps, DefaultValues::getAllOf($type));
        static::attachJson("relationships", $allProps, static::makeRelationshipObject($type));
        static::attachJson("visible-props", $allProps, static::makeCheckbox(VisibleProps::getAllOf($type)));
        static::attachJson("hidden-props", $allProps, static::makeCheckbox(HiddenProps::getAllOf($type)));
        static::attachJson("required-props", $allProps,  static::makeCheckbox(RequiredProps::getAllOf($type)));
        static::attachJson("read-only-props", $allProps, static::makeCheckbox(ReadOnlyProps::getAllOf($type)));
        static::attachJson("hidden-wl-role-sets", $allProps, static::makeCheckbox(HiddenWLProps::getAllOf($type)));
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

    private static function readSettings($type)
    {
        $a = [];
        static::attachJson("settings", $a, static::makeCheckbox(Settings::getAllOf($type)));
        return $a;
    }

    private static function make($type)
    {
        static::$result['problems'] = [];
        static::$result['type'] = $type;
        static::$result['plural'] = Str::plural($type);
        static::$result['props'] = static::readProps($type);
        static::$result['statuses'] = static::readStatuses($type);
        static::$result['settings'] = static::readSettings($type);
        return static::$result;
    }

    public static function getFor($type)
    {
        return static::make($type);
        $key = "super_prop_$type";
        if (!Cache::has($key)) {
            Cache::rememberForever($key, fn () => static::make($type));
        }
        return Cache::get($key);
    }

    public static function invalidateCache($type)
    {
        $key = "super_prop_$type";
        Cache::forget($key);
    }
}
