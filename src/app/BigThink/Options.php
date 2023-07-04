<?php

namespace App\BigThink;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Options
{
    private static $singleton = null;
    private static function getAll()
    {
        if (is_null(static::$singleton)) {
            $all = DB::table('options')->get();
            foreach ($all as $item) $indexed[$item->key][] = json_decode($item->value);
            static::$singleton = collect($indexed);
        }
        return static::$singleton;
    }

    public static function get($key, $default = null, $scalar = false)
    {
        $db = static::getAll();
        if (isset($db[$key])) {
            return $scalar ? $db[$key][0] : $db[$key];
        } else {
            return $default;
        }
    }

    public static function upsert($values)
    {
        $existed = DB::table('options')
            ->whereIn('key', array_map(fn ($c) => $c['key'], $values))
            ->select(['key', 'id'])
            ->get();
        // dump($existed);
        $existedKeys = array_map(fn ($c) => $c->key, $existed->toArray());
        // dump($existedKeys);
        $toBeUpdated = [];
        $toBeInserted = [];
        foreach ($values as $value) {
            if (in_array($value['key'], $existedKeys)) {
                $toBeUpdated[$value['key']] = $value;
            } else {
                $toBeInserted[] = $value;
            }
        }
        // dump($toBeInserted);
        // dump($toBeUpdated);
        $inserted = $updated = 0;
        $inserted += DB::table('options')->insert($toBeInserted);
        foreach ($existed as $line) $updated += DB::table('options')->where('id', $line->id)->update($toBeUpdated[$line->key]);
        // dump($inserted, $updated);
        static::$singleton = null; //Invalidate cache
        return [$inserted, $updated];
    }

    public static function getByKeys(Request $request)
    {
        $keys = $request->input('keys');
        $default = $request->input('default') ?? "";
        $result = [];
        if ($keys) {
            foreach ($keys as $key) {
                $result[$key] = static::get($key, $default, true);
            }
        }
        return ResponseObject::responseSuccess($result, ['keys' => $keys, 'default' => $default]);
    }

    public static function setByKeyValues(Request $request)
    {
        $lines = $request->input('lines');
        if ($lines) [$inserted, $updated] = static::upsert($lines);
        return ResponseObject::responseSuccess(['inserted' => $inserted, 'updated' => $updated]);
    }
}
