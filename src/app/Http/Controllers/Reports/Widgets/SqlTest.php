<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlTest
{
    public function __invoke($params)
    {
        // $sql = "SELECT 1 metric_id, 'A' metric_name, 30 metric_count
        //             UNION ALL
        //         SELECT 2 metric_id, 'B' metric_name, 20 metric_count
        //             UNION ALL
        //         SELECT 3 metric_id, 'C' metric_name, 10 metric_count
        //         ORDER BY metric_count DESC
        //     ";
        // return $sql;
        return ""
    }
}
