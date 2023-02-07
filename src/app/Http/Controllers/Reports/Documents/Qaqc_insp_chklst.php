<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentController;

class Qaqc_insp_chklst extends Report_ParentController
{
    protected $viewName = 'document-qaqc-insp-chklst';

    public function getSqlStr()
    {
        $sql =  " SELECT
                po.id
                ,s.id AS sheet_id
                ,r.id AS run_id
                ,r.updated_at AS run_updated
                ,r.description run_desc

                ,l.id AS line_id
                ,l.description AS line_description
                ,l.name AS line_name
                
                
                ,cv.id AS control_value_id
                ,cv.name AS control_value_name
                ,cv.qaqc_insp_control_group_id control_group_id
            
                ,ct.id AS control_type_id
                ,ct.name AS control_type_name
                ,g.id AS group_id
                ,g.description AS group_description
                ,ts.id AS tmpl_sheet_id
                ,ts.description AS tmpl_sheet_description
                
                ,divide_control.c1
                ,divide_control.c2
                ,divide_control.c3
                ,divide_control.c4
                
                
            FROM qaqc_insp_chklst_runs r
                JOIN qaqc_insp_chklst_shts s ON r.qaqc_insp_chklst_sht_id = s.id
                JOIN prod_orders po ON po.id = '{{po}}'
                JOIN qaqc_insp_chklst_lines l ON l.qaqc_insp_chklst_run_id = r.id
                JOIN control_types ct ON ct.id = l.control_type_id
                LEFT JOIN qaqc_insp_control_values cv ON l.qaqc_insp_control_value_id = cv.id
                JOIN qaqc_insp_groups g ON g.id = l.qaqc_insp_group_id
                JOIN qaqc_insp_tmpl_shts ts ON ts.id = s.qaqc_insp_tmpl_sht_id
                LEFT JOIN (
                        SELECT id as control_group_id
                        , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 1)), '|', 1)) AS c1
                        , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 2)), '|', 1)) AS c2
                        , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 3)), '|', 1)) AS c3
                        , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 4)), '|', 1)) AS c4
                        FROM qaqc_insp_control_groups AS cg
                                )  AS divide_control ON l.qaqc_insp_control_group_id = divide_control.control_group_id
            
            WHERE r.qaqc_insp_chklst_sht_id = '{{sheet_id}}'
            ORDER BY line_name,  run_updated DESC ";
        return $sql;
    }
    public function getTableColumns()
    {
        return [
            [
                "title" => 'Description',
                "dataIndex" => "line_description",
            ],
            [
                "dataIndex" => "response_type",
                "align" => "center",
            ]
        ];
    }

    protected function enrichDataSource($dataSource)
    {

        // dd($columns);
        $circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
        $checkedIcon = "<i class='fa-solid fa-circle-check px-2'></i>";

        $lines =  [];
        foreach ($dataSource as $item) {
            $lines[$item['line_id']] = $item;
        }

        $id_lineDesc = array_column($dataSource, 'line_description', 'line_id');
        $desc_ids = [];
        foreach ($id_lineDesc as $id => $value) {
            $desc_ids[$value][] = $id;
        }
        $ids_htmls = [];
        foreach ($desc_ids as $ids) {
            $str = '';
            foreach ($ids as $id) {
                $item = $lines[$id];
                if (!is_null($item['c1'])) {
                    $arrayControl = ['c1' => $item['c1'], 'c2' => $item['c2'], 'c3' => $item['c3'], 'c4' => $item['c4']];
                    $s = "";
                    foreach ($arrayControl as $col => $value) {
                        if ($item['control_value_name'] === $item[$col]) {
                            $s .= "<td class ='px-6 py-4'>" . $checkedIcon . $value . "</td>";
                        } else {
                            $s .= "<td class ='px-6 py-4'>" . $circleIcon . $value . "</td>";
                        }
                    };
                    $runDesc = "<td>" . $item['run_desc'] . ":" . "</td>";
                    $runUpdated = "<td class ='px-6 py-4'>" . $item['run_updated'] . "</td>";
                    $str .= "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" .  $runDesc  . $s . $runUpdated . "</tr>";
                }
                $ids_htmls[$id] = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $str . "</tbody>" . "</table>";
            }
        }

        $dataRender = [];
        $desc_id = array_column($dataSource, 'line_id', 'line_description');
        foreach ($desc_id as $id) {
            $dataRender[] = $lines[$id] + ['response_type' => $ids_htmls[$id]];
        }
        return $dataRender;
    }
}
