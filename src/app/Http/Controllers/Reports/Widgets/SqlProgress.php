<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlProgress
{
    public function __invoke($params)

    {
        // // dd($params);
        // $table_a = $params['table_a'];
        // $table_b = $params['table_b'];

        // $check_id = $params['check_id'];
        // $tba = $table_a === 'projects' ? $table_a . ' a,' : '';

        // // conditions
        // $condition = "";
        // $condition1  = isset($params['qaqc_insp_tmpl_id']) ? "AND b.qaqc_insp_tmpl_id = {$params['qaqc_insp_tmpl_id']} " : "";
        // $table_order = 'sp';
        // switch ($table_a) {
        //     case 'projects':
        //         $condition = "AND a.id = $check_id
        //                 AND a.id = sp.project_id
        //                 AND sp.id = po.sub_project_id
        //                 AND po.id = b.id \n" . $condition1;
        //         $table_order = 'a';
        //         break;
        //     case 'sub_projects':
        //         $conditionMinor = $check_id ? "AND sp.id = $check_id \n" : '';
        //         $condition = "$conditionMinor
        //                 AND sp.id = po.sub_project_id
        //                 AND po.id = b.id \n" . $condition1;
        //         break;
        //     default:
        //         $condition = "";
        //         break;
        // }
        // $sql = " SELECT
        //             metric_id,
        //             metric_name,
        //             COUNT(*) AS metric_count
        //             FROM (  SELECT
        //                         ROW_NUMBER() OVER (ORDER BY $table_order.id ) AS metric_id,
        //                         CASE
        //                             WHEN b.progress >= 0 AND b.progress <= 20 OR b.progress IS NULL THEN '0%-20%'
        //                             WHEN b.progress >= 20 AND b.progress <= 40 THEN '20%-40%'
        //                             WHEN b.progress >= 40 AND b.progress <= 60 THEN '40%-60%'
        //                             WHEN b.progress >= 60 AND b.progress <= 80 THEN '60%-80%'
        //                             WHEN b.progress >= 80 AND b.progress <= 100 THEN '80%-100%'
        //                         END AS metric_name
        //                         FROM $tba sub_projects sp, prod_orders po, $table_b b
        //                             WHERE 1 = 1
        //                                 $condition ) AS subQuery
        //                             GROUP BY metric_name
        //                             ORDER BY metric_id";
        // // dd($sql, $params);
        return "";
    }
}
