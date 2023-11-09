<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\StringReport;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamSubProjectId extends ParentParamReports
{
    protected $referData = 'project_id';
    protected function getDataSource()
    {
        $statuses = config("project")['active_statuses']['sub_projects'];
        $strStatuses = StringReport::arrayToJsonWithSingleQuotes2($statuses); 
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                        sp.id AS id
                        ,sp.description
                        ,sp.name AS name
                        ,sp.status AS sp_status";
        if ($hasListenTo) $sql .= ",sp.project_id AS project_id";
        $sql .="\n FROM sub_projects sp
                        WHERE sp.deleted_at IS NULL AND sp.status IN ($strStatuses)
                        ORDER BY sp.name";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
