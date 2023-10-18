<?php

namespace App\Http\Controllers\Reports\Widgets;

use Illuminate\Support\Str;

class SqlStatus
{
    public function __invoke($params)
    {

        // $table_widget = $params['table_widget'];
        // $table_b = $params['table_b'] ?? null;
        // $table_c = $params['table_c'] ?? null;

        // $key_b1 = $params['key_b1'] ?? 'id';

        // $global_filter = $params['global_filter'] ?? "1=1";

        // // Selections
        // $check_id = $params['check_id'];
        // $att_name = $params['att_metric_name'];
        // $metric_name_table = 'a';

        // // conditions
        // $condition = "";
        // switch ($table_widget) {
        //     case 'projects':
        //         $condition = "AND a.id = $check_id
        //         AND a.id = sp.project_id
        //         AND sp.id = b.sub_project_id";
        //         $table_c ? $condition .= "\n AND b.id = c.prod_order_id \n" : "";
        //         $metric_name_table = $table_c ? 'c' : 'b';
        //         break;
        //     case 'sub_projects':
        //         $minorCondition = $check_id ? "AND sp.id = $check_id \n" : '';
        //         $condition = "$minorCondition
        //                     AND sp.id = b.sub_project_id";
        //         $table_c ? $condition .= "\n AND b.id = c.prod_order_id \n" : "";
        //         $metric_name_table = $table_c ? 'c' : 'b';
        //         break;
        //     default:
        //         $condition = "";
        //         break;
        // }

        // $tba = $table_widget === 'projects' ? $table_widget . ' a,' : '';
        // $tbc = $table_c  ? ',' . $table_c . ' c' : '';
        // $sql = " SELECT 
        //                 ROW_NUMBER() OVER (ORDER BY metric_count DESC) AS metric_id,
        //                 metric_name, 
        //                 metric_count 
        //                     FROM (
        //                         SELECT 
        //                             $metric_name_table.$att_name AS metric_name, 
        //                             COUNT($metric_name_table.id) AS metric_count 
        //                         FROM $tba sub_projects sp, $table_b b $tbc
        //                         WHERE $global_filter
        //                                 $condition
        //                     GROUP BY metric_name ) tb
        //                     ORDER BY metric_id";
        // dd($params, $sql);
        return "";
    }
}
