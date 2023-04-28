<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlForeignKey_Routing
{
    public function __invoke($params)
    {
        $table_a = $params['table_a'];
        $table_b = $params['table_b'];
        $table_c = $params['table_c'];
        $key_a = $params['key_a'] ?? null;
        $key_b1 = $params['key_b1'] ?? null;
        $key_b2 = $params['key_b2'] ?? null;
        $key_c = $params['key_c'] ?? null;

        $con1  = isset($params['sub_project_id']) ? "AND a.$key_a = {$params['sub_project_id']} " : "";



        $sql = "SELECT a.$key_a metric_id, c.name metric_name, count(b.id) metric_count
            FROM $table_a a, $table_b b, $table_c c
            WHERE 1=1
                AND a.$key_a = b.$key_b1
                AND b.$key_b2 = c.$key_c
                $con1
            GROUP BY metric_name
            ORDER BY metric_count DESC
            ";
        // dd($sql);
        return $sql;
    }
}
