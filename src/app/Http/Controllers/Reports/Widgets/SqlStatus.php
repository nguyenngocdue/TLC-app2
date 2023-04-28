<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlStatus
{
    public function __invoke($params)
    {
        // dump($params);
        $table_a = $params['table_a'];
        $table_b = $params['table_b'] ?? null;

        $key_a1 = $params['key_a1'] ?? null;
        $key_a2 = $params['key_a2'] ?? null;
        $key_b = $params['key_b'] ?? null;
        $global_filter = $params['global_filter'] ?? "1=1";


        // Selections
        $table2 = is_null($table_b) ? "" : ", $table_b b";
        $att_name = $params['att_metric_name'];
        $metric_name_table = is_null($table_b) ? "a" : "b";

        // Conditions
        $con1  = isset($params['sub_project_id']) ? "AND a.$key_a1 = {$params['sub_project_id']} " : "";
        $con2  = is_null($table_b) ? "" : "AND a.$key_a2 =  b.$key_b";


        $sql = " SELECT 
                        ROW_NUMBER() OVER (ORDER BY metric_count DESC) AS metric_id,
                        metric_name, 
                        metric_count 
                            FROM (
                                SELECT 
                                    $metric_name_table.$att_name AS metric_name, 
                                    COUNT(a.id) AS metric_count 
                                FROM $table_a a $table2 
                                WHERE 1 = 1
                                    AND $global_filter
                                        $con1
                                        $con2
                            GROUP BY metric_name ) tb
                            ORDER BY metric_id";
        // dd($sql);
        return $sql;
    }
}
