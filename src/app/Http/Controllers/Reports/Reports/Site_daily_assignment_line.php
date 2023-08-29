<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Site_daily_assignment_line extends Controller

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
        [$startDate, $endDate] = array_map(fn($item) => Report::formatDateString($item), $dates);
        
        $sql = "SELECT
                    sda.id AS site_daily_assignment_id,
                    sda.site_date AS site_date,
                    sda.site_team_id AS site_team_id,
                    sdal.id AS site_daily_assignment_line_id,
                    sdal.user_id AS site_daily_assignment_line_user_id,
                    sdal.sub_project_id AS sub_project_id
                    FROM site_daily_assignments sda, site_daily_assignment_lines sdal
                    WHERE 1 = 1
                    AND sda.id = sdal.site_daily_assignment_id
                    AND sda.deleted_by IS NULL
                    AND sdal.deleted_by IS NULL
                    #AND sdal.site_daily_assignment_id = 13"
                    ;
        if(isset($params['user_team_site_id'])) $sql .= "\n AND sda.site_team_id IN ({{user_team_site_id}})";
        if($startDate) $sql .= "\n AND sda.site_date >= '$startDate'";
        if($endDate) $sql .= "\n AND sda.site_date <= '$endDate'";
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
