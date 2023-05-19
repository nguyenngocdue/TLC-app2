<?php

namespace App\View\Components\Formula;

use App\Models\Term;

class NCR_Report_Type
{
    public function __invoke($item)
    {
        $defectDispositionId = $item['defect_disposition_id'] * 1;
        $defectSeverity = $item['defect_severity'] * 1;
        $defectDispositionIdFromTermTb = 209;
        $defectSeverityIdFromTermTb = 238;
        $defectReportTypeId = 240;
        if ($defectDispositionId === $defectDispositionIdFromTermTb && $defectSeverity === $defectSeverityIdFromTermTb) {
            $defectReportTypeId = 241;
        };
        return $defectReportTypeId;
    }
}
