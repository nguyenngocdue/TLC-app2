<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_030 extends Qaqc_wir_020
{

    protected $mode = '030';
    protected $viewName = 'document-wir-030';


    public function getDataSource($params)
    {
        $ins = new Qaqc_wir_020();
        $dataSource = $ins->getDataSource($params);
        $dataSource = $ins->changeDataSource($dataSource, $params);
        // dd($params);
        return $dataSource;
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_name',
                'width' => 200
            ],
            [
                'title' => 'QTY',
                'dataIndex' => 'total_prod_order',
            ],
            [
                'title' => 'Apartment Q.ty',
                'dataIndex' => 'total_prod_order',
            ],
            [
                // 'title' => Arr::get($params, 'previous_month', '') . 'Production Completion (%)',
                'title' => 'Last Week Production Completion (%)',
                'dataIndex' => 'total_prod_order_on_sub_project',
                'width' => 200,
            ],
            [
                // 'title' => Arr::get($params, 'previous_month', '') . 'Production Completion (%)',
                'title' => 'Last Week QC Acceptance (%)',
                'dataIndex' => 'total_prod_order_on_sub_project',
                'align' => 'right',
                'width' => 180,
            ],
            [
                // 'title' =>  Arr::get($params, 'latest_month', '') . '<br/>Production Completion (%)',
                'title' =>  'This Week Production Completion (%)',
                'dataIndex' => 'previous_qaqc_percent',
                'align' => 'right',
                'width' => 180,
            ],
            [
                // 'title' => Arr::get($params, 'latest_month', '') . '<br/>QC Acceptance (%)',
                'title' => 'This Week QC Acceptance (%)',
                'dataIndex' => 'latest_qaqc_percent',
                'align' => 'right',
                'width' => 180,
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'percent_status',
                'align' => 'center',
            ],
        ];
    }


    private function getProductionsData($params)
    {
        $sqlStr = "SELECT 
                        tb1.*,
                        sp.name AS sub_project_name,
                        pr.name AS prod_routing_name,
                        ROUND(count_prl_previous_finished * 100/ totall_prod_routing_link, 2) persent_previous_finished,
                        ROUND(count_prl_latest_finished * 100/ totall_prod_routing_link, 2) persent_latest_finished
                        FROM(SELECT
                            sequ.sub_project_id AS sub_project_id,
                            sequ.prod_routing_id AS prod_routing_id,
                            COUNT(sequ.prod_routing_link_id) AS totall_prod_routing_link,
                            SUM(CASE WHEN 
                                sequ.status IN ('closed', 'finished', 'approved')
                                AND sequ.closed_at <= '2023-12-03'
                                    THEN 1 ELSE 0 END) count_prl_previous_finished,
                            SUM(CASE WHEN 
                                sequ.status IN ('closed', 'finished', 'approved')
                                AND sequ.closed_at <= '2024-10-03'
                                    THEN 1 ELSE 0 END) count_prl_latest_finished
                            FROM prod_sequences sequ 
                            WHERE 1 = 1
                            AND sequ.status NOT IN ('not_applicable', 'cancelled')
                            GROUP BY sub_project_id, prod_routing_id ) tb1
                        LEFT JOIN sub_projects sp ON sp.id = tb1.sub_project_id
                        LEFT JOIN prod_routings pr ON pr.id = tb1.prod_routing_id";
        $sqlData = DB::select($sqlStr);
        return collect($sqlData);
    }


    public function changeDataSource($dataSource, $params)
    {
        $data = Report::getItemsFromDataSource($dataSource);

        $groupQaqcByProjects = Report::groupArrayByKey($data, 'sub_project_id');
        unset($groupQaqcByProjects[""]);

        $fieldsNeedToAvg = ['latest_qaqc_percent', 'previous_qaqc_percent'];
        $fieldsNeedToSum = ['total_prod_order_on_sub_project'];

        $outputQaqc = [];
        // qaqc_wir
        foreach ($groupQaqcByProjects as $key => $items) {
            $avgValues = ArrayReport::averageArrayByKeys($items, $fieldsNeedToAvg);
            $prodOrderSum = ArrayReport::summaryArrayByKeys($items, $fieldsNeedToSum);
            $arrayMerge = array_merge($avgValues, $prodOrderSum);

            $firstItem = reset($items);
            $values = array_merge(
                array_intersect_key(
                    $firstItem,
                    array_flip([
                        'project_id', 'sub_project_status', 'project_name',
                        'sub_project_id', 'sub_project_name', 'total_prod_order_on_sub_project'
                    ])
                ),
                $arrayMerge
            );
            $outputQaqc[$key] = $values;
        }

        // Productions
        $productionsData = $this->getProductionsData($params);
        $productionsData = Report::getItemsFromDataSource($productionsData);
        $groupProdByProjects = Report::groupArrayByKey($productionsData, 'sub_project_id');

        // merge data of QAQC & Productions
        $firstItemQaqc = reset($outputQaqc);
        $firstItemQaqc = array_keys($firstItem);
        $firstItemQaqc = array_fill_keys($firstItemQaqc, NULL);

        foreach ($groupProdByProjects as $subProjectId => &$value) {
            $value = current($value);
            if (isset($outputQaqc[$subProjectId])) {
                $valueProdOrders = $outputQaqc[$subProjectId];
                $value = array_merge($value, $valueProdOrders);
            } else {
                $diffItems = array_diff_key($firstItemQaqc, $value);
                $value = array_merge($value, $diffItems);
                // dd($value);
            }
        }
        // dd($groupProdByProjects);
        return collect($groupProdByProjects);
    }
}
