<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlProgress
{
    public function __invoke($params)

    {
        $table_a = $params['table_a'];
        $table_c = $params['table_c'];
        $key_a = $params['key_a'] ?? null;
        $key_c = $params['key_c'] ?? null;

        $con1  = isset($params['sub_project_id']) ? "AND a.$key_a = {$params['sub_project_id']} " : "";
        $con2  = isset($params['qaqc_insp_tmpl_id']) ? "AND c.qaqc_insp_tmpl_id = {$params['qaqc_insp_tmpl_id']} " : "";

        $sql = "SELECT
                    ROW_NUMBER() OVER (ORDER BY a.id ) AS metric_id,
                    CASE
                        WHEN c.progress >= 0 AND c.progress <= 20 OR c.progress IS NULL THEN '0%-20%'
                        WHEN c.progress >= 20 AND c.progress <= 40 THEN '20%-40%'
                        WHEN c.progress >= 40 AND c.progress <= 60 THEN '40%-60%'
                        WHEN c.progress >= 60 AND c.progress <= 80 THEN '60%-80%'
                        WHEN c.progress >= 80 AND c.progress <= 100 THEN '80%-100%'
                    END AS metric_name,
                    COUNT(*) AS metric_count
                    FROM $table_a a, prod_orders po, $table_c c
                        WHERE 1 = 1
                            AND a.$key_a = po.sub_project_id    
                            AND po.$key_a = c.$key_c
                            $con1
                            $con2
                        GROUP BY metric_name
                        ORDER BY metric_name
                        ";
        // dd($sql, $params);
        return $sql;
    }
}
