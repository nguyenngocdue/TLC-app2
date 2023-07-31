<?php

namespace App\View\Components\Formula;

use App\Models\Term;

class NCR_Report_Type
{
    public function __invoke($item)
    {
        define("DISPOSITION_REPAIR", 209);
        define("SEVERITY_LOW", 238);
        define("DFR", 240);
        define("NCR", 241);

        $defectDispositionId = $item['defect_disposition_id'] * 1;
        $defectSeverity = $item['defect_severity'] * 1;
        $defectReportTypeId = NCR;
        if ($defectDispositionId === DISPOSITION_REPAIR && $defectSeverity === SEVERITY_LOW) {
            $defectReportTypeId = DFR;
        };
        return $defectReportTypeId;
    }
}
