<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_ncr_dataSource extends Controller
{
    use TraitCreateSQL;
    use TraitGenerateValuesFromParamsReport;
    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        // dd($valOfParams, $params);
        $sql = "SELECT tb1.*
                    ,pj.name AS project_name
                    ,sp.name AS sub_project_name
                    ,pr.name AS prod_routing_name
                    ,po.name AS prod_order_name
                    ,pdisc.name AS prod_discipline_name
                    ,ustncr.name AS user_team_name
                    ,prio.name AS priority_name
                    ,term_root_cause.name AS root_cause_name,
                    term_disposition.name AS disposition_name,
                    term_severity.name AS severity_name,
                    term_report_type.name AS report_type_name,
                    term_inter_subcon.name AS inter_subcon_name,
                    LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '\\\', -1), '\\\', 1)) AS parent_type_route,
                    LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '\\\', -1), '\\\', 1)) AS parent_type,
                    tb1.parent_id AS parent_type_id, 
                    IF (LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '\\\', -1), '\\\', 1)) = 'qaqc_wir', 
                        (SELECT wir.name FROM qaqc_wirs wir WHERE tb1.parent_id = wir.id),
                        IF (LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '\\\', -1), '\\\', 1)) = 'qaqc_mir',
                            (SELECT mir.name FROM qaqc_mirs mir WHERE tb1.parent_id = mir.id),
                            (SELECT cll.name FROM qaqc_insp_chklst_lines cll WHERE tb1.parent_id = cll.id)
                           )
                    ) AS source_name,
                    CONCAT('TLC-',sp.name,'-',IF(term_report_type.name = 'NCR' OR term_report_type.name = 'Defect','NCR',
                    SUBSTRING_INDEX(term_report_type.name, ' ', -1)
                    ),'-',
                        LPAD(tb1.doc_id, 4, '0')) AS doc_type 
                    FROM (SELECT
                    DATE_FORMAT(SUBSTR(ncr.created_at, 1, 10), '%d/%m/%Y') AS create_date,
                        ncr.parent_type AS parent_type,
                        ncr.id AS ncr_id,
                        ncr.doc_id AS doc_id,
                        ncr.name AS ncr_name,
                        ncr.parent_id AS parent_id,
                        ncr.project_id AS project_id,
                        ncr.sub_project_id AS sub_project_id,
                        ncr.prod_routing_id AS prod_routing_id,
                        ncr.prod_order_id AS prod_order_id,
                        ncr.prod_discipline_id AS prod_discipline_id,
                        ncr.user_team_id AS user_team_id,
                        ncr.priority_id AS priority_id,
                        DATE_FORMAT(SUBSTR(ncr.due_date, 1, 10), '%d/%m/%Y') AS due_date,
                        ncr.defect_root_cause_id AS root_cause,
                        ncr.defect_disposition_id AS disposition_id,
                        ncr.defect_severity AS severity,
                        ncr.defect_report_type AS report_type,
                        ncr.inter_subcon_id AS inter_subcon_id,
                        ncr.qty_man_power AS qty_man_power,
                        ncr.hour_per_man AS hour_per_man,
                        ncr.total_hour AS total_hour,
                        ncr.status AS ncr_status,
                        DATE_FORMAT(SUBSTR(ncr.closed_at, 1, 10), '%d/%m/%Y') AS closed_at
                        FROM qaqc_ncrs ncr
                    WHERE 1 = 1
                        AND ncr.deleted_by IS NULL
                        AND SUBSTR(ncr.created_at, 1, 10) >= '{$valOfParams["picker_date"]["start"]}'
                        AND SUBSTR(ncr.created_at, 1, 10) <= '{$valOfParams["picker_date"]["end"]}'";
        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND ncr.project_id = {$valOfParams['project_id']}";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND ncr.sub_project_id = {$valOfParams['sub_project_id']}";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND ncr.prod_routing_id = {$valOfParams['prod_routing_id']}";
        if (Report::checkValueOfField($valOfParams, 'prod_order_id')) $sql .= "\n AND ncr.prod_order_id IN ({$valOfParams['prod_order_id']})";
        if (Report::checkValueOfField($valOfParams, 'prod_discipline_id'))  $sql .= "\n AND ncr.prod_discipline_id IN ({$valOfParams['prod_discipline_id']})";
        if (Report::checkValueOfField($valOfParams, 'user_team_ncr'))  $sql .= "\n AND ncr.user_team_id IN ({$valOfParams['user_team_ncr']})";
        if (Report::checkValueOfField($valOfParams, 'report_type'))  $sql .= "\n AND ncr.defect_report_type IN ({$valOfParams['report_type']})";
        if (Report::checkValueOfField($valOfParams, 'status'))  $sql .= "\n AND ncr.status IN ({$valOfParams['status']})";
        if (Report::checkValueOfField($valOfParams, 'root_cause'))  $sql .= "\n AND ncr.defect_root_cause_id IN ({$valOfParams['root_cause']})";

        $sql .= "\n ) AS tb1
                    LEFT JOIN projects pj ON pj.id = tb1.project_id
                    LEFT JOIN sub_projects sp ON sp.id = tb1.sub_project_id
                    LEFT JOIN prod_orders po ON po.id = tb1.prod_order_id 
                    LEFT JOIN prod_routings pr ON pr.id = tb1.prod_routing_id
                    LEFT JOIN prod_disciplines pdisc ON pdisc.id = tb1.prod_discipline_id
                    LEFT JOIN user_team_ncrs ustncr ON ustncr.id = tb1.user_team_id
                    LEFT JOIN priorities prio ON prio.id = tb1.priority_id
                    LEFT JOIN terms term_root_cause ON term_root_cause.id = tb1.root_cause";
        $sql .= "\n LEFT JOIN terms term_disposition ON term_disposition.id = tb1.disposition_id
                    LEFT JOIN terms term_severity ON term_severity.id = tb1.severity
                    LEFT JOIN terms term_report_type ON term_report_type.id = tb1.report_type
                    LEFT JOIN terms term_inter_subcon ON term_inter_subcon.id = tb1.inter_subcon_id
                ";
        return $sql;
    }

    public function getDataSource($params)
    {
        $sql = $this->getSql($params);
        // dd($sql);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select($sql);
        $collection = collect($sqlData);
        return $collection;
    }

    public function changeDataSource($dataSource, $params)
    {
        $manageApps = LibApps::getAll();
        foreach ($dataSource as &$values) {
            $parentType = $values->parent_type;
            $nickName = $manageApps[$parentType]['nickname'] ?? $values->parent_type;
            $values->parent_type = strtoupper($nickName);
        }
        return $dataSource;
    }
}
