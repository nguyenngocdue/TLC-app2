<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_020 extends Qaqc_wir_010
{

    protected $mode = '020';
    protected $viewName = 'document-wir-010';

    public function getDataSource($params)
    {
        $ins = new Qaqc_wir_010();
        $dataSource = $ins->getDataSource($params);
        $dataSource = $ins->changeDataSource($dataSource, $params);
        return $dataSource;
    }

    protected function basicInfoWidgetReport()
    {
        return [
            'key' => 'qaqc_wir_overall_complete_status_all_projects',
            'title_report' => "The All Projects Acceptance Report",
        ];
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_name',
                'width' => 150,
            ],
            [
                'title' => 'QTY',
                'dataIndex' => 'number_of_prod_orders',
                'align' => 'right',
            ],
            [
                'title' => 'Apartment Q.ty',
                'dataIndex' => 'total_prod_order',
                'align' => 'right',
            ],
            [
                'title' => 'Last Week Production Completion (%)',
                'dataIndex' => 'previous_finished_prod_percent',
                'width' => 200,
                'align' => 'right',
            ],
            [
                'title' => 'Last Week QC Acceptance (%)',
                'dataIndex' => 'previous_qaqc_percent',
                'align' => 'right',
                'width' => 180,
                'align' => 'right',
            ],
            [
                'title' =>  'This Week Production Completion (%)',
                'dataIndex' => 'latest_finished_prod_percent',
                'align' => 'right',
                'width' => 180,
                'align' => 'right',
            ],
            [
                'title' => 'This Week QC Acceptance (%)',
                'dataIndex' => 'latest_qaqc_percent',
                'align' => 'right',
                'width' => 180,
                'align' => 'right',
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'percent_status',
                'align' => 'center',
            ],
        ];
    }

    public function changeDataSource($dataSource, $params)
    {
        $dataSource =  Report::getItemsFromDataSource($dataSource);
        $groupBySubProjects = Report::groupArrayByKey($dataSource, 'sub_project_id');
        $result = [];
        $fieldsNeedToAvg = [
            'latest_qaqc_percent', 'previous_qaqc_percent',
            'latest_finished_prod_percent', 'previous_finished_prod_percent'
        ];
        $fieldsNeedToSum = ['number_of_prod_orders'];
        foreach ($groupBySubProjects as $key => $items) {
            $avgValues = ArrayReport::averageArrayByKeys($items, $fieldsNeedToAvg);
            $prodOrderSum = ArrayReport::summaryArrayByKeys($items, $fieldsNeedToSum);
            $arrayMerge = array_merge($avgValues, $prodOrderSum);

            $firstItem = reset($items);
            $values = array_merge(
                array_intersect_key(
                    $firstItem,
                    array_flip([
                        'project_id', 'project_name',
                        'sub_project_id', 'sub_project_name', 'number_of_prod_orders', 'percent_status'
                    ])
                ),
                $arrayMerge
            );
            // dd($values);
            $result[$key] = $values;
        }

        $result = array_values($result);
        return collect($result);
    }
}
