<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlStatus
{
    public function __invoke($params)
    {
        $table_a = $params['table_a'];
        $key_a = $params['key_a'] ?? null;
        $global_filter = $params['global_filter'] ?? "1=1";

        $sql = "SELECT
                    ROW_NUMBER() OVER (ORDER BY a.$key_a) AS metric_id,
                    a.$key_a AS metric_name,
                    COUNT(a.$key_a) AS metric_count
                        FROM  $table_a a
                            WHERE 1 = 1
                            AND $global_filter
                        GROUP BY metric_name
                        ORDER BY metric_count DESC";
        return $sql;
    }
}



case 1: production_order_01
    
{"table_a":"sub_projects", "att_metric_name":"status"}

Case 2: 	subProject_Order_01
{"table_a":"sub_projects", "key_a":"status", "table_b":"prod_orders", "key_b":"id", "global_filter":"a.id != 21" }