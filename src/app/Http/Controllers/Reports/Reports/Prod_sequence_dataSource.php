<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Prod_sequence_dataSource extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    protected $maxH = 50;

    public function getSqlStr($params)
    {

        $valOfParams = Report::createValueForParams([
            'sub_project_id',
            'project_id',
            'prod_routing_id',
            'prod_order_id',
            'prod_routing_link_id',
            'erp_routing_link_id',
            'prod_discipline_id',
            'month'
        ], $params);

        // dump($valOfParams);
        $sql = "SELECT tb1.*
                    FROM (SELECT
                    sp.project_id AS project_id,
                    sp.id AS sub_project_id,
                    pr.id AS prod_routing_id,
                    po.id  AS prod_order_id,
                    prde.prod_routing_link_id AS prod_routing_link_id,
                    prde.erp_routing_link_id AS erp_routing_link_id,
                    pd.id AS prod_discipline_id,
                    ps.total_man_hours AS total_man_hours,
                    ps.id AS prod_sequence_id,
                    SUBSTR(pru.date, 1, 7) AS `month`,
                    pru.date AS date_prod_run,
                    pru.start,
                    pru.end,
                    ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60, 2) AS hours,
                    pru.worker_number AS pru_worker_number,
                    ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60, 2) * pru.worker_number AS man_hours
                    FROM 
                        sub_projects sp, 
                        prod_orders po, 
                        prod_routings pr, 
                        prod_sequences ps, 
                        prod_routing_links prl,
                        prod_routing_details prde,
                        prod_disciplines pd,
                        prod_runs pru
                    WHERE 1 = 1";
        if ($sub = $valOfParams['project_id']) $sql .= "\n AND sp.project_id = $sub";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND po.sub_project_id =  $sub";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";
        if ($sub = $valOfParams['prod_order_id']) $sql .= "\n AND ps.prod_order_id =  $sub";
        if ($prl = $valOfParams['prod_routing_link_id']) $sql .= "\n AND prde.prod_routing_link_id IN ($prl)";
        if ($prl = $valOfParams['prod_discipline_id']) $sql .= "\n AND prl.prod_discipline_id IN ($prl)";
        
        $sql .= "\n 
                    AND po.sub_project_id = sp.id
                    AND po.prod_routing_id = pr.id
                    AND ps.prod_order_id = po.id
                    AND prl.id = ps.prod_routing_link_id
                    AND prde.prod_routing_link_id = ps.prod_routing_link_id
                    AND prde.prod_routing_id = pr.id
                    AND prde.prod_routing_link_id = prl.id
                    AND pd.id = prl.prod_discipline_id
                    AND pru.prod_sequence_id = ps.id
                    AND  SUBSTR(pru.date, 1, 7) = '{$valOfParams["month"]}'
                    ) AS tb1";
        // dump($sql);
        return $sql;
    }


    public function getDataSource($params)
    {
        $sql = $this->getSql($params);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }
}
