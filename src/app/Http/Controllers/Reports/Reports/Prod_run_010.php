<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Prod_routing_link;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_run_010 extends Report_ParentController

{
    use TraitReport;
    protected  $sub_project_id = 21;
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
        ,ROUND(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0) AS man_minute
        FROM ( SELECT
            sp.id AS sub_project_id
            ,sp.name AS sub_project_name
            ,po.id AS po_id
            ,po.name AS po_name
            ,ps.id AS prod_sequence_id
            ,prl.name AS prod_routing_link_name
            ,prl.id AS prod_routing_link_id
             ,ps.total_hours AS total_hours
        FROM  sub_projects sp, prod_orders po, prod_sequences ps, prod_routing_links prl
        WHERE 1 = 1
        AND sp.id = po.sub_project_id";
        if (empty($modeParams)) $sql  .= "\n AND po.sub_project_id =" . $this->sub_project_id;
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}' \n";
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'\n ";
        if (isset($modeParams['prod_routing_link_id'])) $sql .= "\n AND prl.id = '{{prod_routing_link_id}}'\n ";
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
        if (empty($modeParams)) $sql  .= "\n AND po.sub_project_id =" . $this->sub_project_id;
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id =" . $modeParams['sub_project_id'];
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id =" . $modeParams['prod_order_id'];
        if (isset($modeParams['prod_routing_link'])) $sql .= "\n AND prl.id = '{{prod_routing_link}}'\n ";
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
                "align" => "center",
                'width' => "100"
            ],
            [
                'title' => 'Prod Order Name',
                "dataIndex" => "po_name",
                "align" => "center",
                'width' => "100"
            ],
            [
                "dataIndex" => "prod_routing_link_name",
                "align" => 'right',
                'width' => "400"
            ],
            [
                'title' => 'Date',
                "dataIndex" => "prod_run_date",
                "align" => "center",
                'width' => "100"
            ],
            [
                'title' => 'Workers',
                "dataIndex" => "prod_run_worker_number",
                "align" => 'right',
                'width' => "100"
            ],
            [
                'title' => 'Prod Run Start Time',
                "dataIndex" => "prod_run_start",
                "align" => "center",
                'width' => "100"
            ],
            [
                'title' => 'Prod Run End Time',
                "dataIndex" => "prod_run_end",
                "align" => "center",
                'width' => "100"
            ],
            [
                'title' => 'Duration (hours)',
                "dataIndex" => "prod_run_duration",
                "align" => "center",
                'width' => "100"
            ],
            [
                'title' => 'Total Prod Time (minute)',
                "dataIndex" => "sum_production_time_minutes",
                "align" => 'right',
                'width' => "100"
            ],
            [
                'title' => 'Total Prod Time (hours)',
                "dataIndex" => "sum_production_time_hours",
                "align" => 'right',
                'width' => "100"
            ],
            [
                "title" => "Total Workers",
                "dataIndex" => "total_worker",
                "align" => 'right',
                'width' => "100"
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
            ],
            [
                'title' => 'Production Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true
            ],
            [
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true
            ]
        ];
    }

    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prodOrders  = ['prod_order_id' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        $prodRoutingLinks = ['prod_routing_link_id' => Prod_routing_link::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $prodOrders, $prodRoutingLinks);
    }


    protected function enrichDataSource($dataSource, $modeParams)
    {

        $dataHours = $this->getMaxProdRunIdAndTotalHours($modeParams);
        $dataHours = Report::pressArrayTypeAllItems($dataHours);
        $dataHours = Report::assignKeyByKey($dataHours, 'max_prod_run_id');

        $itemsSource = $dataSource->all();
        $itemsSource = Report::pressArrayTypeAllItems($itemsSource);


        // Calculate all of man (minute)
        $groupIdsProdSeq = Report::groupArrayByKey($itemsSource, 'prod_sequence_id');
        $sumManMinutesProSeq = [];
        foreach ($groupIdsProdSeq as $key => $sequence) {
            $man_minutes = array_column($sequence, 'man_minute');
            $sumManMinutesProSeq[$key] = array_sum($man_minutes);
        }



        // dd($itemsSource, $sumManMinutesProSeq, $dataHours);

        foreach ($itemsSource as $key => $value) {
            $value['prod_routing_link_name'] = (object) [
                'cell_title' => 'ID:' . $value['prod_routing_link_id'],
                'value' => $value['prod_routing_link_name']
            ];

            if (isset($dataHours[$value['prod_run_id']])) {
                $dt = $dataHours[$value['prod_run_id']];
                $man_minute = $sumManMinutesProSeq[$dt['prod_sequence_id']];
                // dd($sumManMinutesProSeq, $man_minute);
                $totalWorker = round($man_minute * 1 / $dt['sum_production_time_minutes'] * 1, 2);

                // dd($value);
                $itemsSource[$key] = $value +
                    [
                        'sum_production_time_hours' => $dt['sum_production_time_hours'],
                        'sum_production_time_minutes' => $dt['sum_production_time_minutes'],
                        'total_worker' => $totalWorker
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
        // dd($itemsSource[0]);
        return collect($itemsSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        // dd($modeParams, $isNullModeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
        }
        return $modeParams;
    }
}
