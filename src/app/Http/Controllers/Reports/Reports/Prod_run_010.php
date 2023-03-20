<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_run_010 extends Report_ParentController

{
    use TraitReport;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT prlTb.*
        ,pr.id AS prod_run_id
        ,pr.date AS prod_run_date
        ,SUBSTR(pr.start, 1,5) AS prod_run_start
        ,SUBSTR(pr.end,1,5) AS prod_run_end
        ,pr.worker_number AS prod_run_worker_number
        ,SUBSTR(TIMEDIFF(pr.end,pr.start), 1, 5) AS prod_run_duration
        ,ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0) AS total_production_time_minute
        ,ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60/60,2) AS total_production_time
        FROM ( SELECT
            sp.id AS sub_project_id
            ,sp.name AS sub_project_name
            ,po.id AS po_id
            ,po.name AS po_name
            ,ps.id AS prod_sequence_id
            ,prl.name AS prod_routing_link_name
             ,ps.total_hours AS total_hours
        FROM  sub_projects sp, prod_orders po, prod_sequences ps, prod_routing_links prl
        WHERE 1 = 1
        AND sp.id = po.sub_project_id";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}' \n";
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'\n ";
        $sql .= "\n AND ps.prod_order_id = po.id
        AND ps.prod_routing_link_id = prl.id) AS prlTb
        JOIN prod_runs pr ON pr.prod_sequence_id = prlTb.prod_sequence_id";
        return $sql;
    }

    public function getMaxProdRunIdAndTotalHours($modeParams)
    {
        // if (empty($modeParams)) dd(123);
        $sql = "SELECT prlTb.*
            , MAX(pr.id) AS max_prod_run_id
            , SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) AS sum_production_time_minutes
            , SUM(pr.worker_number) AS total_worker_all_run
            , COUNT(pr.id) AS count_run_id
            , SUM(pr.worker_number)/ COUNT(pr.id) AS total_worker
            FROM (
            SELECT sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    po.id AS po_id,
                    po.name AS po_name,
                    ps.id AS prod_sequence_id,
                    prl.name AS prod_routing_link_name,
                    ps.total_hours AS sum_production_time_hours
            FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_routing_links prl
            WHERE 1 = 1
                AND sp.id = po.sub_project_id";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id =" . $modeParams['sub_project_id'];
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id =" . $modeParams['prod_order_id'];
        $sql .= "\n AND ps.prod_order_id = po.id
                AND ps.prod_routing_link_id = prl.id
            ) AS prlTb
            JOIN prod_runs pr ON pr.prod_sequence_id = prlTb.prod_sequence_id
            GROUP BY prlTb.prod_sequence_id";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        return [
            [
                "dataIndex" => "sub_project_name",
                "align" => 'center'
            ],
            [
                'title' => 'Production Order Name',
                "dataIndex" => "po_name",
                "align" => 'center'
            ],
            [
                "dataIndex" => "prod_sequence_id",
                "align" => 'right'
            ],
            [
                'title' => 'Date',
                "dataIndex" => "prod_run_date",
                "align" => 'center'
            ],
            [
                'title' => 'Worker',
                "dataIndex" => "prod_run_worker_number",
                "align" => 'right'
            ],

            [
                'title' => 'Prod Run Start Time',
                "dataIndex" => "prod_run_start",
                "align" => 'center'
            ],
            [
                'title' => 'Prod Run End Time',
                "dataIndex" => "prod_run_end",
                "align" => 'center'
            ],
            [
                'title' => 'Total production time (minute)',
                "dataIndex" => "sum_production_time_minutes",
                "align" => 'right'
            ],
            [
                'title' => 'Total production time (hours)',
                "dataIndex" => "sum_production_time_hours",
                "align" => 'right'
            ],
            [
                "dataIndex" => "total_worker",
                "align" => 'right'
            ],


        ];
    }

    protected function getDataModes()
    {
        return ['mode_option' => ['010' => 'Model 010']];
    }
    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'allowClear' => true
            ],
            [
                'title' => 'Production Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true
            ]
        ];
    }

    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prod_orders  = ['prod_order_id' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $prod_orders);
    }


    protected function enrichDataSource($dataSource, $modeParams)
    {

        $dataHours = $this->getMaxProdRunIdAndTotalHours($modeParams);
        $dataHours = Report::pressArrayTypeAllItems($dataHours);
        $dataHours = Report::assignKeyByKey($dataHours, 'max_prod_run_id');

        $itemsSource = $dataSource->all();
        $itemsSource = Report::pressArrayTypeAllItems($itemsSource);

        foreach ($itemsSource as $key => $value) {
            if (isset($dataHours[$value['prod_run_id']])) {
                $dt = $dataHours[$value['prod_run_id']];
                $itemsSource[$key] = $value +
                    [
                        'sum_production_time_hours' => $dt['sum_production_time_hours'],
                        'sum_production_time_minutes' => $dt['sum_production_time_minutes'],
                        'total_worker' => $dt['total_worker']
                    ];
            } else {
                $itemsSource[$key] = $value +
                    [
                        'sum_production_time_hours' => "",
                        'sum_production_time_minutes' => "",
                        'total_worker' => ''
                    ];
            }
        }

        return collect($itemsSource);
    }
}
