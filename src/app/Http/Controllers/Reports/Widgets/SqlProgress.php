<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlProgress
{
    public function __invoke($params)
    {
        $sql = "SELECT 1 metric_id, '0%-20%' metric_name, 20 metric_count
                    UNION ALL
                SELECT 2 metric_id, '20%-40%' metric_name, 20 metric_count
                    UNION ALL
                SELECT 3 metric_id, '40%-60%' metric_name, 20 metric_count
                    UNION ALL
                SELECT 4 metric_id, '60%-80%' metric_name, 20 metric_count
                    UNION ALL
                SELECT 5 metric_id, '80%-100%' metric_name, 20 metric_count
                ORDER BY metric_count DESC
            ";
        return $sql;
    }
}
