<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Models\Eco_sheet;

class Eco_sheet_140 extends Report_ParentReport2Controller
{

    public function getSqlStr($params)
    {
        [$month, $projectId] = $this->selectMonth($params);
        $sql = "SELECT 	
                    #ecosheet.ecos_id AS eco_sheet_id, 
                    usecos.term_id AS user_id,
                    us.full_name AS user_name,
                    GROUP_CONCAT(ecosheet.ecos_id) AS ecos_ids,
                    COUNT(ecosheet.ecos_id) AS count_ecos,
                    SUM(ROUND(TIMESTAMPDIFF(HOUR, ecos_create_at,sign_updated_at)/24,2)) AS latency
                                FROM (SELECT 
                                ecos.id AS ecos_id
                                ,ecos.due_date AS ecos_create_at
                                FROM eco_sheets ecos
                                WHERE 1 = 1
                                AND ecos.project_id = $projectId
                                AND SUBSTR(ecos.due_date, 1, 7) = '$month'
                                #AND ecos.status = 'active'
                                ) AS ecosheet
                                LEFT JOIN ( SELECT
                                                esum1.id AS field_id,
                                                esum1.eco_sheet_id AS doc_id,
                                                esum1.user_id AS term_id
                                                FROM ym2m_eco_sheet_user_monitor_1 esum1
                                                WHERE 1 = 1 
                                            ) AS usecos ON usecos.doc_id =  ecosheet.ecos_id
                                LEFT JOIN (SELECT sign.id AS sign_id, sign.owner_id AS sign_ower_id, sign.signable_id AS ecos_id, sign.updated_at AS sign_updated_at
                                        FROM signatures sign
                                        WHERE 1 = 1
                                        AND sign.signable_type = 'App\\\Models\\\Eco_sheet') AS signeco ON signeco.ecos_id = ecosheet.ecos_id
                                        AND signeco.sign_ower_id = usecos.term_id
                                LEFT JOIN users us ON us.id = usecos.term_id
                                GROUP BY user_id,user_name
                                ORDER BY user_name";
        // dump($sql);
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ]
        ];
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "User to Sign",
                "dataIndex" => "user_name",
                "align" => "left",
                'width' => 30

            ],
            [
                "title" => "ECOs",
                "dataIndex" => "ecos_ids",
                "align" => "left",
                'width' => 30
            ],
            [
                "title" => "Quantity",
                "dataIndex" => "count_ecos",
                "align" => "right",
                'width' => 30

            ],
            [
                "title" => "Latency (days)",
                "dataIndex" => "latency",
                "align" => "right",
                'width' => 30
            ],
        ];
    }

    public function getDisplayValueColumns()
    {
        return [
            [
                'user_name' => [
                    'route_name' => 'users.edit'
                ],
            ]
        ];
    }

    public function changeDataSource($dataSource, $params)
    {
        // dd($dataSource);
        foreach ($dataSource as $key => &$items) {
            if ($strIds = $items['ecos_ids']) {
                $ids = explode(',', $strIds);
                $strName = "";
                foreach ($ids as $id) {
                    $ecoName = Eco_sheet::find($id)->toArray()['name'];
                    $route = route('eco_sheets.edit', $id);
                    $strName .= '<a  class="text-blue-800" href="' . $route . '" >' . $ecoName . '</a>' . "</br>";
                }
                $items['ecos_ids'] = (object)['value' => $strName,];
                $dataSource[$key] = $items;
            }
        }
        return $dataSource;
    }
}
