<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use Illuminate\Support\Facades\DB;

class Site_daily_assignment_dataSource1 extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;
    protected $maxH = 50;

    public function getSqlStr($params)
    {
        $pickerDate = $params['picker_date'] ?? DateReport::defaultPickerDate();
        [$startDate, $endDate] = DateReport::explodePickerDate($pickerDate, 'Y-m-d');
        $sql = "SELECT
                    sda.id AS site_daily_assignment_id,
                    sda.site_date AS site_date,
                    sda.site_team_id AS site_team_id,
                    sdal.id AS site_daily_assignment_line_id,
                    mtm_site_team.count_user AS count_user_site_team,
                    sdal.user_id AS site_daily_assignment_line_user_id,
                    sdal.sub_project_id AS sub_project_id
                    FROM site_daily_assignments sda
                    INNER JOIN site_daily_assignment_lines sdal ON sda.id = sdal.site_daily_assignment_id
                    LEFT JOIN (
                            SELECT
                                    mtm.doc_id AS  site_team_id,
                                    count(mtm.term_id) AS count_user
                                    FROM many_to_many mtm 
                                    WHERE 1 = 1
                                    AND mtm.doc_type = 'App\\\Models\\\user_team_site'
                                    AND mtm.term_type = 'App\\\Models\\\user'
                                    GROUP BY mtm.doc_id
                    ) mtm_site_team ON mtm_site_team.site_team_id = sda.site_team_id
                    WHERE 1 = 1
                    AND sda.deleted_by IS NULL
                    AND sdal.deleted_by IS NULL
                    #AND sdal.site_daily_assignment_id = 13";
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
