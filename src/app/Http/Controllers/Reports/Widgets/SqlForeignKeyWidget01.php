<?php

namespace App\Http\Controllers\Reports\Widgets;

use App\Utils\Support\DBTable;
use Illuminate\Support\Str;

class SqlForeignKeyWidget01
{
    public function __invoke($params)
    {
        // $table_widget = $params['table_widget'];
        // $table_a = $params['table_a'];
        // $table_b = ($params['table_b'] ?? "") ? ', ' . $params['table_b'] . ' b' : '';

        // $wd_tb = $table_widget;
        // if ($table_widget === 'projects') $wd_tb = 'projects, sub_projects,';
        // // if ($table_widget === 'sub_projects') $wd_tb = 'sub_projects,';
        // if ($table_widget === 'sub_projects') $wd_tb = '';


        // $key_a1 = $params['key_a1'] ?? 'id';
        // $key_a2 = $params['key_a2'] ?? 'id';
        // $key_b1 = $params['key_b1'] ?? 'id';

        // $global_filter = $params['global_filter'] ?? "1=1";

        // // Selections
        // $check_id = $params['check_id'];
        // $att_name = $params['att_metric_name'];
        // $metric_name_table = $table_b ? 'b' : 'a';

        // // conditions
        // $con = "";
        // $model_a = DBTable::fromNameToModel($table_a);
        // $fillable = $model_a->getFillable();
        // switch ($table_widget) {
        //     case 'projects':
        //         if (isset($fillable['project_id']) && isset($fillable['sub_project_id'])) {
        //             $con .= "AND a.project_id = $check_id";
        //         } else {
        //             $con .= "AND $table_widget.id = sub_projects.project_id
        //              AND a.$key_a1 = sub_projects.id
        //              AND $table_widget.id = $check_id";
        //         }
        //         $table_b ? $con .= "\n AND a.$key_a2 = b.$key_b1" : "";
        //         break;
        //     case 'sub_projects':
        //         $con = "\n AND a.$key_a1 = $check_id";
        //         $table_b ? $con .= "\n AND a.$key_a2 = b.$key_b1" : "";
        //         break;
        //     case null:
        //         $table_b ? $con .= "\n AND a.$key_a2 = b.$key_b1" : "";
        //         break;
        //     default:
        //         break;
        // }
        // //private conditions
        // $qaqc_insp_tmpl_id = ($x = $params['qaqc_insp_tmpl_id'] ?? "") ? "AND b.qaqc_insp_tmpl_id = $x " : "";
        // $table_widget_deleted_at  = $wd_tb ? "AND projects.deleted_at IS NULL \n AND sub_projects.deleted_at IS NULL":"";
        // $table_a_deleted_at  = "AND a.deleted_at IS NULL ";
        // $table_b_deleted_at  = $table_b ? "AND b.deleted_at IS NULL ":"";

        // $sql = " SELECT 
        //                 ROW_NUMBER() OVER (ORDER BY metric_count DESC) AS metric_id,
        //                 metric_name, 
        //                 metric_count 
        //                     FROM (
        //                         SELECT 
        //                             $metric_name_table.$att_name AS metric_name, 
        //                             COUNT($metric_name_table.id) AS metric_count 
        //                         FROM $wd_tb $table_a a $table_b
        //                         WHERE $global_filter
        //                                 $con
        //                                 $qaqc_insp_tmpl_id #qaqc_insp_chklsts
        //                                 $table_widget_deleted_at
        //                                 $table_a_deleted_at
        //                                 $table_b_deleted_at
        //                     GROUP BY metric_name ) tb
        //                     ORDER BY metric_id";
        // dump($params, $sql);
        return "";
    }
}
