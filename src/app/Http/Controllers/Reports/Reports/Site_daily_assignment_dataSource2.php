<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\DateReport;
use Illuminate\Support\Facades\DB;

class Site_daily_assignment_dataSource2 extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    protected $maxH = 50 * 16;

    public function getSqlStr($params)
    {
        $pickerDate = $params['picker_date'] ?? DateReport::defaultPickerDate();
        [$startDate, $endDate] = DateReport::explodePickerDate($pickerDate, 'Y-m-d');
        $valOfParams = DateReport::createValueForParams([
            'prod_routing_id',
            'prod_routing_link_id',
            'sub_project_id',
            'user_id'
        ], $params);

        // dump($valOfParams);
        $sql = "SELECT
                        sda.site_date AS site_date,
                        sdal.sub_project_id AS sub_project_id,
                        sda.id AS site_daily_assignment_id,
                        sdal.id AS site_daily_assignment_line_id,
                        mtm_sdal.prod_routing_link_id AS prod_routing_link_id,
                        sda.site_team_id AS site_team_id,
                        pr.id AS prod_routing_id,
                        sdal.user_id AS user_id,
                        sdal.employeeid AS employeeid
                        FROM site_daily_assignments sda, site_daily_assignment_lines sdal
                        LEFT JOIN ( SELECT
                                        mtm.doc_id AS site_daily_assignment_line_id,
                                        mtm.term_id AS prod_routing_link_id
                                        FROM many_to_many mtm 
                                        WHERE 1 = 1
                                        AND mtm.doc_type = 'App\\\Models\\\Site_daily_assignment_line'
                                        AND mtm.term_type = 'App\\\Models\\\Prod_routing_link'
                                        ) mtm_sdal ON mtm_sdal.site_daily_assignment_line_id = sdal.id
                        LEFT JOIN prod_routing_details  prd ON prd.prod_routing_link_id = mtm_sdal.prod_routing_link_id
                        LEFT JOIN prod_routings pr ON pr.id = prd.prod_routing_id
                        WHERE 1 = 1
                        AND sda.id = sdal.site_daily_assignment_id
                        AND sda.deleted_by IS NULL
                        AND sdal.deleted_by IS NULL";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND sdal.sub_project_id IN ($sub)";
        if ($us = $valOfParams['user_id']) $sql .= "\n AND sdal.user_id IN ($us)";
        if ($prl = $valOfParams['prod_routing_link_id']) $sql .= "\n AND mtm_sdal.prod_routing_link_id IN ($prl)
                                             AND mtm_sdal.prod_routing_link_id IS NOT NULL";
        if (isset($params['user_team_site_id'])) $sql .= "\n AND sda.site_team_id IN ({{user_team_site_id}})";
        if ($startDate) $sql .= "\n AND sda.site_date >= '$startDate'";
        if ($endDate) $sql .= "\n AND sda.site_date <= '$endDate'";
        // dump($sql);
        return $sql;
    }

    public function getDataSource($params)
    {
        $sql = $this->getSql($params);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select($sql);
        $collection = collect($sqlData);
        return $collection;
    }
}
