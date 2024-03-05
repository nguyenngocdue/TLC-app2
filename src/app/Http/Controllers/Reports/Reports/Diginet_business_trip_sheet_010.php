<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Diginet_business_trip_sheet_010 extends Report_ParentReport2Controller
{

    protected $viewName = "report-diginet-data";
    protected $maxH = 30;
    public function getSqlStr($params)
    {
        $sql = "SELECT dgnts.*
                FROM diginet_business_trip_sheets dgnts
                ";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            []
        ];
    }

    protected function getTableColumns($params, $dataSource)
    {
        return $this->generateTableColumns($dataSource);
    }
}
