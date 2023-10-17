<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Project;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamReportType extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT
                        term.id AS id,
                        term.name AS name,
                        term.description AS description 
                        FROM qaqc_ncrs ncr, terms term
                        WHERE 1 = 1
                        AND ncr.defect_report_type = term.id
                        GROUP BY term.id
                        ORDER BY term.name";
        $result = DB::select($sql);
        return $result;
    }
}
