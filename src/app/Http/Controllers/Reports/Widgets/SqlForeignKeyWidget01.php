<?php

namespace App\Http\Controllers\Reports\Widgets;

use App\Utils\Support\DBTable;
use Illuminate\Support\Str;

class SqlForeignKeyWidget01
{
    public function __invoke($params)
    {
        $table_widget = $params['table_widget'];
        $table_a = $params['table_a'];
        $table_b = ($params['table_b'] ?? "") ? ', ' . $params['table_b'] . ' b' : '';

        $key_a1 = $params['key_a1'] ?? 'id';
        $key_a2 = $params['key_a2'] ?? '';
        $key_b1 = $params['key_b1'] ?? 'id';
        $key = $key_a2 ? $key_a2 : $key_a1;

        $global_filter = $params['global_filter'] ?? "1=1";

        // Selections
        $check_id = $params['check_id'];
        $att_name = $params['att_metric_name'];
        $metric_name_table = $table_b ? 'b' : 'a';

        // cons
        $con = "";
        $model_a = DBTable::fromNameToModel($table_a);
        $fillable = $model_a->getFillable();
        // dd($fillable);
        switch ($table_widget) {
            case 'projects':
                isset($fillable['project_id']) ?
                    $con .= "AND a.project_id = $check_id" :
                    "AND $table_widget.id = sub_projects.project_id
                     AND $table_a.$key_a1 = sub_projects.id";
                $table_b ? $con .= "\n AND a.$key = b.$key_b1" : "";
                break;
            case 'sub_projects':
                $con = "AND a.sub_project_id = $check_id";
                $table_b ? $con .= "\n AND a.$key = b.$key_b1" : "";
                break;
            case null:
                $table_b ? $con .= "\n AND a.$key = b.$key_b1" : "";
                break;
            default:
                break;
        }

        $sql = " SELECT 
                        ROW_NUMBER() OVER (ORDER BY metric_count DESC) AS metric_id,
                        metric_name, 
                        metric_count 
                            FROM (
                                SELECT 
                                    $metric_name_table.$att_name AS metric_name, 
                                    COUNT($metric_name_table.id) AS metric_count 
                                FROM $table_a a $table_b
                                WHERE $global_filter
                                        $con
                            GROUP BY metric_name ) tb
                            ORDER BY metric_id";
        // dd($params, $sql);
        return $sql;
    }
}
