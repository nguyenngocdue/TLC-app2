<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Site_daily_assignment_dataSource2 extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    protected $maxH = 50;
    protected $mode = '100';
    #protected $rotate45Width = 300;
    protected $libPivotFilters;

    public function getSqlStr($params)
    {
        $dates = Report::explodePickerDate($params['picker_date']);
        [$startDate, $endDate] = array_map(fn ($item) => Report::formatDateString($item), $dates);
        $prodRoutingLinkIds = isset($params['prod_routing_link_id']) ? implode(',', $params['prod_routing_link_id']) : '';
        $subProjectIds = isset($params['sub_project_id']) ? implode(',', $params['sub_project_id']) : '';
        $userIds = isset($params['user_id']) ? implode(',', $params['user_id']) : '';
        // dump($prodRoutingLinkId);
        $sql = "SELECT
                        sda.site_date AS site_date,
                        sdal.sub_project_id AS sub_project_id,
                        sda.id AS site_daily_assignment_id,
                        sdal.id AS site_daily_assignment_line_id,
                        mtm_sdal.prod_routing_link_id AS prod_routing_link_id,
                        sda.site_team_id AS site_team_id,
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
                        WHERE 1 = 1
                        AND sda.id = sdal.site_daily_assignment_id
                        AND sda.deleted_by IS NULL
                        AND sdal.deleted_by IS NULL";
        if($subProjectIds) $sql .= "\n AND sdal.sub_project_id IN ($subProjectIds)";
        if($userIds) $sql .= "\n AND sdal.user_id IN ($userIds)";
        if ($prodRoutingLinkIds) $sql .= "\n AND mtm_sdal.prod_routing_link_id IN ($prodRoutingLinkIds)
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
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }
}
