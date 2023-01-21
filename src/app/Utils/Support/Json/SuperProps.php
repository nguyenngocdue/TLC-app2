<?php

namespace App\Utils\Support\Json;

use App\BigThink\ModelExtended;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SuperProps
{
    private static $result = [];
    private static function attachRelationship(&$allProps, $type)
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
                    $column_name = $eloquentParams[substr($key, 1)][2];
                    $result[$column_name] = $rls;
                    break;
                case 'hasMany': //"posts" => ['hasMany', Post::class, 'owner_id', 'id'],
                case 'belongsToMany': //"prodRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
                case 'hasManyThrough': // "prodSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],
                case "morphTo": //"attachable" => ['morphTo', Attachment::class, 'object_type', 'object_id'],
                case "morphOne": //"avatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
                case "morphMany": //"comment_by_clinic" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
                case 'getCheckedByField': // "mainAffectedPart()" => ["getCheckedByField", Term::class],
                    $column_name = substr($key, 1);
                    $result[$column_name] = $rls;
                    break;
                    // case 'hasOne':
                default:
                    static::$result['problems']['orphan_relationships'][] = $rls['name'] . " - " . $rls['relationship'];
                    break;
            }
        }
        // dump("RESULT");
        // dump($result);
        foreach ($allProps as $key => &$prop) {
            $column_name = $prop['column_name'];
            // dump($column_name);
            if (isset($result[$column_name])) {
                $prop['relationship'] = $result[$column_name];
            }
        }
    }

    private static function attachDefaultValues(&$allProps, $type)
    {
        $allDefaultValues = DefaultValues::getAllOf($type);
        foreach ($allProps as $key => &$prop) {
            if (isset($allDefaultValues[$key])) {
                $defaultValues = $allDefaultValues[$key];
                foreach ($defaultValues as $a => $defaultValue) {
                    $prop[$a] = $defaultValue;
                }
            }
        }
    }

    private static function readProps($type)
    {
        $allProps = Props::getAllOf($type);
        static::attachDefaultValues($allProps, $type);
        static::attachRelationship($allProps, $type);
        return $allProps;
    }

    private static function make($type)
    {
        static::$result['type'] = $type;
        static::$result['plural'] = Str::plural($type);
        static::$result['props'] = static::readProps($type);
        static::$result['problems'] = [];
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
