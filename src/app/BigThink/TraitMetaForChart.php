<?php

namespace App\BigThink;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitMetaForChart
{
    function sqlForeignKey($params)
    {
        // return $params;
        $table_a = $params['table_a'];
        $table_b = $params['table_b'];
        $key_a = $params['key_a'] ?? null;
        $key_b = $params['key_b'] ?? null;
        $global_filter = $params['global_filter'] ?? "1=1";

        $key_a = $key_a ?? Str::singular($table_b) . "_id";
        $key_b = $key_b ?? "id";
        // Log::info("$table_a, $table_b, $key_a, $key_b, $global_filter");

        $sql = "SELECT a.$key_a metric_id, b.name metric_name, count(a.id) metric_count
            FROM $table_a a, $table_b b
            WHERE 1=1
                AND $global_filter
                AND b.$key_b=a.$key_a
            GROUP BY $key_a
            ORDER BY metric_count DESC
            ";
        // return $sql;
        return DB::select($sql);
    }

    function getMetaForChart($fn, $params)
    {
        return $this->{$fn}($params);
    }
}
