<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Utils\Support\DateReport;
use Illuminate\Support\Facades\DB;

class Prod_sequence_dataSource extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    use TraitGenerateValuesFromParamsReport;

    protected $maxH = 50;

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);

        // dd($valOfParams);
        if (isset($valOfParams['picker_date']) && is_string($valOfParams['picker_date'])) {
            $strDate = DateReport::defaultPickerDate();
            $pickerDate = DateReport::separateStrPickerDate($strDate);
            $valOfParams['picker_date'] = $pickerDate;
        }
        $sql = "SELECT tb1.*, terms.name AS uom
                    FROM (SELECT
                    sp.project_id AS project_id,
                    sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    pr.id AS prod_routing_id,
                    pr.name AS prod_routing_name,
                    po.id  AS prod_order_id,
                    po.name  AS prod_order_name,
                    prde.prod_routing_link_id AS prod_routing_link_id,
                    prde.erp_routing_link_id AS erp_routing_link_id,
                    pd.id AS prod_discipline_id,
                    pd.name AS prod_discipline_name,
                    pru.prod_discipline_id AS prod_discipline_line_id,
                    (SELECT pdisc.name FROM prod_disciplines pdisc WHERE pru.prod_discipline_id =  pdisc.id) AS prod_discipline_line_name,
                    #ps.total_man_hours AS total_man_hours,
                    ps.id AS prod_sequence_id,
                    ps.erp_prod_order_name AS erp_prod_order_name,
                    ps.status AS prod_sequence_status,
                    SUBSTR(pru.date, 1,7) AS month_prod_run,
                    pru.date AS date_prod_run,
                    IF(pru.is_rework = 0, 'Design Change', 'Workmanship') AS is_rework,
                    '{$valOfParams["picker_date"]["start"]}' AS from_date,
                    '{$valOfParams["picker_date"]["end"]}' AS to_date,

                    #MIN(pru.date) AS from_date,
                    #MAX(pru.date) AS to_date,

                    prde.order_no AS order_no,
                    #pru.start,
                    #pru.end,
                    ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60, 2) AS hours,
                    ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60, 2)*pru.worker_number AS total_man_hours,
                    #pru.worker_number AS pru_worker_number,
                    #ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start))/60/60, 2) * pru.worker_number AS man_hours
                    pru.total_man_hours AS man_hours,
                    pru.start AS from_time,
                    pru.end AS to_time,
                    pru.id AS pru_id,
                    ps.total_hours AS total_hours,
                    pru.worker_number AS man_power,
                    ps.total_uom AS total_uom,
                    prl.standard_uom_id AS standard_uom_id,
                    pru.production_output AS production_output,
                    pru.remark AS remark,
                    us.full_name AS prod_run_owner_name,
                    GROUP_CONCAT(cm.content) AS content_comment,
                    cm.owner_id AS comment_owner_id
                    FROM 
                        sub_projects sp
                        JOIN prod_orders po ON po.sub_project_id = sp.id
                        JOIN prod_routings pr ON po.prod_routing_id = pr.id
                        JOIN prod_sequences ps ON ps.prod_order_id = po.id AND ps.deleted_by IS NULL
                        JOIN prod_routing_links prl ON prl.id = ps.prod_routing_link_id
                        JOIN prod_routing_details prde ON prde.prod_routing_link_id = prl.id 
                                                        AND prde.prod_routing_id = pr.id
                                                        AND prde.prod_routing_link_id = ps.prod_routing_link_id
                        JOIN prod_disciplines pd ON pd.id = prl.prod_discipline_id
                        JOIN prod_runs pru ON pru.prod_sequence_id = ps.id AND pru.deleted_by IS NULL
                        LEFT JOIN users us ON us.id = pru.owner_id
                        LEFT JOIN comments cm ON cm.commentable_type = 'App\\\Models\\\Prod_sequence' AND cm.commentable_id = ps.id
                    WHERE 1 = 1";
        if ($pj = $valOfParams['project_id']) $sql .= "\n AND sp.project_id IN ($pj)";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND po.sub_project_id IN ($sub)";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";


        if ($po = $valOfParams['prod_order_id']) $sql .= "\n AND ps.prod_order_id IN($po)";
        if ($prl = $valOfParams['prod_routing_link_id']) $sql .= "\n AND prde.prod_routing_link_id IN ($prl)";
        if ($pd = $valOfParams['prod_discipline_id']) $sql .= "\n AND prl.prod_discipline_id IN ($pd)";
        if ($erp = $valOfParams['erp_routing_link_id']) $sql .= "\n AND prde.erp_routing_link_id IN ($erp)";
        if ($status = $valOfParams['status']) $sql .= "\n AND ps.status IN( $status )";

        $sql .= "\n 
                    AND  SUBSTR(pru.date, 1, 10) >= '{$valOfParams["picker_date"]["start"]}'
                    AND  SUBSTR(pru.date, 1, 10) <= '{$valOfParams["picker_date"]["end"]}'
                    GROUP BY comment_owner_id, pr.id, po.id, prde.prod_routing_link_id, pru.date, pru.end, pru.start, pru.worker_number, pru.total_man_hours, pru.id
                    ) AS tb1
                    LEFT JOIN terms terms ON tb1.standard_uom_id = terms.id
                    ORDER BY sub_project_name, prod_routing_name, prod_discipline_name, order_no, prod_order_name
                    ";
        // dump($sql);
        return $sql;
    }


    public function getDataSource($params)
    {
        $sql = $this->getSql($params);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select($sql);
        // sort by order_no
        uasort($sqlData, function ($a, $b) {
            return $a->order_no - $b->order_no;
        });
        $collection = collect($sqlData);
        return $collection;
    }
}
