<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_ncr_030_dataSource extends Controller

{
    use TraitCreateSQL;
    public function getSqlStr($params)
    {
        $sql = "";
        return $sql;
    }

    protected function getAllSqlStr()
    {
        $OPEN_CLOSED_ISSUES = "SELECT
                                DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                                DATE_FORMAT(ncr.due_date, '%Y') AS year,
                                DATE_FORMAT(ncr.due_date, '%m') AS month,
                                SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,
                                SUM(CASE
                                    WHEN ncr.status = 'closed' THEN 1
                                    ELSE 0
                                END) AS count_closed,
                                SUM(CASE
                                    WHEN ncr.status != 'closed' THEN 1
                                    ELSE 0
                                END) AS count_new
                            FROM qaqc_ncrs ncr
                            WHERE 1 = 1
                            AND ncr.deleted_by IS NULL
                            GROUP BY date_time, year ,month, str_month
                            ORDER BY date_time";

        $NCR_DR = "SELECT
                        DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                        DATE_FORMAT(ncr.due_date, '%Y') AS year,
                        DATE_FORMAT(ncr.due_date, '%m') AS month,
                        SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,
                        SUM(
                            CASE
                                WHEN ncr.defect_report_type = 240 THEN 1 ELSE 0 END 
                        ) AS count_defect,
                        SUM(
                            CASE WHEN ncr.defect_report_type = 241 THEN 1 ELSE 0 END
                        ) AS count_ncr
                        FROM qaqc_ncrs ncr
                        LEFT JOIN terms term ON term.id = ncr.defect_report_type
                        WHERE 1 = 1 
                            AND ncr.deleted_by IS NULL
                        GROUP BY date_time, year, month, str_month";

        $RESPONSIBLE_TEAM = "SELECT
                        DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                        DATE_FORMAT(ncr.due_date, '%Y') AS year,
                        DATE_FORMAT(ncr.due_date, '%m') AS month,
                        SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,                    
                        SUM(
                                CASE
                                    WHEN ncr.user_team_id = 10 THEN 1 ELSE 0 END 
                            ) AS count_structure,
                        SUM(
                                CASE
                                    WHEN ncr.user_team_id = 7 THEN 1 ELSE 0 END 
                            ) AS count_ppr,
                        SUM(
                                CASE
                                    WHEN ncr.user_team_id != 7 AND ncr.user_team_id != 10  THEN 1 ELSE 0 END 
                            ) AS count_fit_out
                        FROM qaqc_ncrs ncr
                        LEFT JOIN user_team_ncrs ncrt ON ncrt.id = ncr.user_team_id
                        WHERE 1 = 1 
                            AND ncr.deleted_by IS NULL
                        GROUP BY date_time, year, month, str_month";

        $AVERAGE_CLOSED_ISSUES = "SELECT
                                DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                                DATE_FORMAT(ncr.due_date, '%Y') AS year,
                                DATE_FORMAT(ncr.due_date, '%m') AS month,
                                SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,
                                ROUND(SUM(CASE
                                    WHEN ncr.status = 'closed' THEN 1
                                    ELSE 0
                                END)/COUNT(ncr.id),2) AS avg_closed,
                                SUM(CASE
                                    WHEN ncr.status != 'closed' THEN 1
                                    ELSE 0
                                END) AS count_new
                            FROM qaqc_ncrs ncr
                            WHERE 1 = 1
                            AND ncr.deleted_by IS NULL
                            GROUP BY date_time, year ,month, str_month
                            ORDER BY date_time";

        $ISSUES_STATUS = "SELECT
                            DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                            DATE_FORMAT(ncr.due_date, '%Y') AS year,
                            DATE_FORMAT(ncr.due_date, '%m') AS month,
                            SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,
                            SUM(
                                    CASE
                                        WHEN ncr.status = 'closed' THEN 1 ELSE 0 END 
                                ) AS count_closed,
                            SUM(
                                CASE
                                    WHEN ncr.status = 'new' THEN 1 ELSE 0 END 
                            ) AS count_new,
                            SUM(
                                CASE
                                    WHEN ncr.status = 'rejected' THEN 1 ELSE 0 END 
                            ) AS count_rejected,
                            SUM(
                                CASE
                                    WHEN ncr.status = 'resolved' THEN 1 ELSE 0 END 
                            ) AS count_resolved

                            FROM qaqc_ncrs ncr
                            WHERE 1 = 1 
                                AND ncr.deleted_by IS NULL
                            GROUP BY date_time, year ,month, str_month
                            ORDER BY date_time";

        $ISSUES_SOURCE = "SELECT date_time,
                            year,
                            month,
                            str_month,
                            SUM(count_wir) AS sum_count_wir,
                            SUM(count_mir) AS sum_count_mir,
                            SUM(count_isp) AS sum_count_isp,
                            SUM(count_wir + count_mir + count_isp) AS grand_total
                            FROM (SELECT
                                DATE_FORMAT(ncr.due_date,'%Y-%m') AS date_time,
                                DATE_FORMAT(ncr.due_date, '%Y') AS year,
                                DATE_FORMAT(ncr.due_date, '%m') AS month,
                                SUBSTR(DATE_FORMAT(ncr.due_date, '%M'), 1, 3) AS str_month,
                                SUM(
                                    CASE
                                        WHEN 
                            REPLACE(LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(ncr.parent_type, '\\\', -1), '\\\\',-1)), 'qaqc_', '') = 'WIR' THEN 1 ELSE 0 END 
                                    ) AS count_wir,
                                SUM(
                                    CASE
                                        WHEN 
                            REPLACE(LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(ncr.parent_type, '\\\', -1), '\\\\',-1)), 'qaqc_', '') = 'MIR' THEN 1 ELSE 0 END 
                                    ) AS count_mir,
                                SUM(
                                    CASE
                                        WHEN 
                            REPLACE(LOWER(SUBSTRING_INDEX(SUBSTRING_INDEX(ncr.parent_type, '\\\', -1), '\\\\',-1)), 'qaqc_', '') = 'insp_chklst_line' THEN 1 ELSE 0 END 
                                    ) AS count_isp
                                FROM qaqc_ncrs ncr
                                LEFT JOIN user_team_ncrs ncrt ON ncrt.id = ncr.user_team_id            

                                WHERE 1 = 1 
                                    AND ncr.deleted_by IS NULL
                               GROUP BY date_time, year, month, str_month
                                ) AS derived_table
                                GROUP BY date_time, year, month, str_month
                                ORDER BY date_time";
        return [
            "OPEN_CLOSED_ISSUES" => $OPEN_CLOSED_ISSUES,
            "NCR_DR" => $NCR_DR,
            "RESPONSIBLE_TEAM" => $RESPONSIBLE_TEAM,
            "ISSUES_STATUS" => $ISSUES_STATUS,
            "ISSUES_SOURCE" => $ISSUES_SOURCE,
            "AVERAGE_CLOSED_ISSUES" => $AVERAGE_CLOSED_ISSUES
        ];
    }

    private function makeSqlStr($params)
    {
        $conditionsStr  = "";

        if (Report::checkValueOfField($params, 'only_month')) {
            $onlyMonth = $params['only_month'];
            $onlyMonthStr = implode(',', $onlyMonth);
            $conditionsStr .= "\n AND DATE_FORMAT(ncr.due_date, '%m') IN ({$onlyMonthStr})";
        }
        if (Report::checkValueOfField($params, 'year')) $conditionsStr .= "\n AND DATE_FORMAT(ncr.due_date, '%Y') = {$params['year']}";
        if (Report::checkValueOfField($params, 'project_id')) $conditionsStr .= "\n AND ncr.project_id = {$params['project_id']}";
        if (Report::checkValueOfField($params, 'sub_project_id')) $conditionsStr .= "\n AND ncr.sub_project_id = {$params['sub_project_id']}";
        if (Report::checkValueOfField($params, 'prod_routing_id')) $conditionsStr .= "\n AND ncr.prod_routing_id = {$params['prod_routing_id']}";
        if (Report::checkValueOfField($params, 'prod_order_id')) $conditionsStr .= "\n AND ncr.prod_order_id IN ({$params['prod_order_id']})";
        if (Report::checkValueOfField($params, 'prod_discipline_id'))  $conditionsStr .= "\n AND ncr.prod_discipline_id IN ({$params['prod_discipline_id']})";
        if (Report::checkValueOfField($params, 'user_team_ncr'))  $conditionsStr .= "\n AND ncr.user_team_id IN ({$params['user_team_ncr']})";
        if (Report::checkValueOfField($params, 'report_type'))  $conditionsStr .= "\n AND ncr.defect_report_type IN ({$params['report_type']})";
        if (Report::checkValueOfField($params, 'status'))  $conditionsStr .= "\n AND ncr.status IN ({$params['status']})";
        if (Report::checkValueOfField($params, 'root_cause'))  $conditionsStr .= "\n AND ncr.defect_root_cause_id IN ({$params['root_cause']})";
        if (Report::checkValueOfField($params, 'root_cause'))  $conditionsStr .= "\n AND ncr.defect_root_cause_id IN ({$params['root_cause']})";

        // dd($conditionsStr);
        $allSqlStr = $this->getAllSqlStr();
        foreach ($allSqlStr as $key => $value) {
            $allSqlStr[$key] = implode(" 1 = 1 \n" . $conditionsStr . "\n", explode("1 = 1", $value));
        }
        return $allSqlStr;
    }


    public function getDataSource($params)
    {
        $sqls = $this->makeSqlStr($params);
        $dataSource = [];
        foreach ($sqls as $key => $sqlStr) {
            if (is_null($sqlStr) || !$sqlStr) return collect();
            $sqlData = DB::select(DB::raw($sqlStr));
            $dataSource[$key] = $sqlData;
        }
        return collect($dataSource);
    }
}
