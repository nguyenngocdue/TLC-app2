<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Utils\Support\Report;

class Qaqc_insp_chklst extends Report_ParentController
{
    protected $viewName = 'document-qaqc-insp-chklst';

    public function getSqlStr($urlParams)
    {
        $sql =  " SELECT
                        po.id
                        ,sp.name AS project_name
                        ,l.value AS sign
                        ,l.value_comment AS value_comment
                        ,r.qaqc_insp_chklst_sht_id AS sheet_id
                        ,s.description AS sheet_name
                        ,r.id AS run_id
                        ,r.updated_at AS run_updated
                        ,r.description run_desc

                        ,l.id AS line_id
                        ,l.description AS line_description
                        ,l.name AS line_name
                        
                        
                        ,cv.id AS control_value_id
                        ,cv.name AS control_value_name
                        ,g.description AS group_description
                        ,ts.id AS tmpl_sheet_id
                        ,ts.description AS tmpl_sheet_description
                        
                        ,divide_control.c1
                        ,divide_control.c2
                        ,divide_control.c3
                        ,divide_control.c4
                

            FROM qaqc_insp_chklst_runs r
                JOIN qaqc_insp_chklst_shts s ON r.qaqc_insp_chklst_sht_id = s.id";

        if (isset($urlParams['prod_order_id'])) $sql .= " \n JOIN prod_orders po ON po.id = '{{prod_order_id}}' \n";
        $sql .= " \n JOIN qaqc_insp_chklst_lines l ON l.qaqc_insp_chklst_run_id = r.id
                JOIN control_types ct ON ct.id = l.control_type_id
                JOIN sub_projects sp ON sp.id = po.sub_project_id
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
            
                        WHERE 1=1";
        if (isset($urlParams['sheet_id'])) $sql .= " \n AND r.qaqc_insp_chklst_sht_id = '{{sheet_id}}' \n";
        $sql .= "\n ORDER BY line_name,  run_updated DESC ";
        return $sql;
    }



    public function getTableColumns($dataSource = [])
    {
        return [
            [
                "title" => 'Description',
                "dataIndex" => "line_description",
                'width' => "253"
            ],
            [
                "dataIndex" => "response_type",
                "align" => "center",
            ],

        ];
    }

    protected function enrichDataSource($dataSource)
    {


        $circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
        $checkedIcon = "<i class='fa-solid fa-circle-check px-2'></i>";

        $lines =  [];
        foreach ($dataSource as $item) {
            if (isset($item['line_id'])) {
                $lines[$item['line_id']] = $item;
            }
        }

        $desc_lineIds = [];
        foreach ($lines as $id => $value) {
            $desc = $value['line_description'];
            $desc_lineIds[$desc][] = $id;
        }

        $arrayHtml = [];
        foreach ($desc_lineIds as $ids) {
            $str = '';
            foreach ($ids as $id) {
                $item = $lines[$id];
                if (!is_null($item['c1'])) {
                    $arrayControl = ['c1' => $item['c1'], 'c2' => $item['c2'], 'c3' => $item['c3'], 'c4' => $item['c4']];
                    $s = "";
                    foreach ($arrayControl as $col => $value) {
                        if ($item['control_value_name'] === $item[$col]) {
                            $s .= '<td class="border" style="width:50px">' . $checkedIcon . $value . '</td>';
                        } else {
                            $s .=  '<td class="border" style="width:50px">' . $circleIcon . $value . '</td>';
                        }
                    };
                    $runDesc =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . $item['run_desc'] . ":" . "</td>" : "";
                    $runUpdated = '<td class="border" style="width:80px" >' . $item['run_updated'] . "</td>";

                    $pictures = '<td class="border" style="width:190px">' . '<div class="flex">' . $this->getImage() . '</div>' . '</td>';
                    // dump($item);
                    $value_comment = '<td class="border" style="width:190px">' .  $item['value_comment'] . '</td>';

                    $showItems = $runDesc . $s . $runUpdated . $pictures . $value_comment;

                    $str .= "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $showItems . "</tr>";
                } else {
                    $str .=  $item['sign'];
                }
                $arrayHtml[$id] = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $str . "</tbody>" . "</table>";
            }
        }

        foreach ($lines as $id => $value) {
            $lines[$id]['response_type'] = $arrayHtml[$id];
        }
        $sheetGroup = Report::groupArrayByKey($lines, 'sheet_id');

        $sheets = [];
        foreach ($sheetGroup as $sheetId => $value) {
            $groupDesc = Report::groupArrayByKey($value, 'line_description');
            foreach ($groupDesc as $key => $value) {
                $groupDesc[$key] = array_pop($value);
            }
            // dd($groupDesc);
            $sheets[$sheetId] = $groupDesc;
        }

        $data = [];
        foreach ($sheets as $sheetId => $runLines) {
            $data[$sheetId] = array_values($runLines);
        }
        ksort($data);
        // dd($data);
        return $data;
    }

    protected function getImage()
    {
        return "
        <img title='dev-thuc/2023/02/incident trong tran van-150x150.png' width='64' class='rounded-lg object-cover border mr-1' src='http://192.168.100.100:9000/hello-001/dev-thuc/2023/02/incident trong tran van-150x150.png'>
        <img title='avatars/admin avatar-150x150.png' width='64' class='rounded-lg object-cover border mr-1' src='http://192.168.100.100:9000/hello-001/avatars/admin avatar-150x150.png'>
        <img title='avatars/admin avatar-150x150.png' width='64' class='rounded-lg object-cover border mr-1' src='http://192.168.100.100:9000/hello-001/avatars/admin avatar-150x150.png'>
        <img title='avatars/admin avatar-150x150.png' width='64' class='rounded-lg object-cover border mr-1' src='http://192.168.100.100:9000/hello-001/avatars/admin avatar-150x150.png'>
        ";
    }
}
