<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;

class Qaqc_wir_030 extends Qaqc_wir_020
{

    protected $mode = '030';
    protected $viewName = 'document-wir-030';


    public function getDataSource($params)
    {
        // dd($params);
        $ins = new Qaqc_wir_020();
        $dataSource = $ins->getDataSource($params);
        $dataSource = $ins->changeDataSource($dataSource, $params);
        return $dataSource;
    }


    public function changeDataSource($dataSource, $params)
    {
        $data = Report::getItemsFromDataSource($dataSource);
        $groupByProjects = Report::groupArrayByKey($data, 'project_id');
        unset($groupByProjects[""]);

        $result = [];
        $fieldsNeedToAvg = ['latest_acceptance_percent', 'previous_acceptance_percent'];
        $fieldsNeedToSum = ['total_prod_order_on_sub_project'];
        foreach ($groupByProjects as $key => $items) {
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
            $result[$key] = $values;
        }
        return collect($result);
    }
}
