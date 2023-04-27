<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlProgress
{
    public function __invoke($params)

    {
        $global_filter = $params['global_filter'] ?? "1=1";
        $sql = "SELECT
                    ROW_NUMBER() OVER (ORDER BY sp.id ) AS metric_id,
                    CASE
                        WHEN chlst.progress >= 0 AND chlst.progress <= 20 OR chlst.progress IS NULL THEN '0%-20%'
                        WHEN chlst.progress >= 20 AND chlst.progress <= 40 THEN '20%-40%'
                        WHEN chlst.progress >= 40 AND chlst.progress <= 60 THEN '40%-60%'
                        WHEN chlst.progress >= 60 AND chlst.progress <= 80 THEN '60%-80%'
                        WHEN chlst.progress >= 80 AND chlst.progress <= 100 THEN '80%-100%'
                    END AS metric_name,
                    COUNT(*) AS metric_count
                    FROM sub_projects sp, prod_orders po, qaqc_insp_chklsts chlst
                        WHERE sp.id = po.sub_project_id    
                            AND $global_filter
                            AND po.id = chlst.prod_order_id
                        GROUP BY metric_name
                        ORDER BY metric_name
                        ";
        return $sql;
    }
}
