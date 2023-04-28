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
        $key_a = $params['key_a'] ?? null;
        $key_b = $params['key_b'] ?? null;

        $att_metric_name = $params['att_metric_name'];

        $global_filter = $params['global_filter'] ?? "1=1";

        $pr1 = is_null($table_b) ? "a" : "b";
        $tb1 = $pr1 === "b" ? ", $table_b b" : "";
        $f1 = $pr1 === "b" ? "AND a.$key_a = b.$key_b" : "";

        $sql = "SELECT
                    ROW_NUMBER() OVER (ORDER BY $pr1.$att_metric_name) AS metric_id,
                    $pr1.$att_metric_name AS metric_name,
                    COUNT($pr1.$att_metric_name) AS metric_count
                        FROM  $table_a a $tb1
                            WHERE 1 = 1
                            AND $global_filter
                            $f1
                        GROUP BY metric_name
                        ORDER BY metric_count DESC";
        // dump($sql);
        return $sql;
    }
}
