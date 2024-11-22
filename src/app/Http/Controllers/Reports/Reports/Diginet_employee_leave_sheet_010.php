<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Diginet_employee_leave_sheet_010 extends Report_ParentReport2Controller
{

    protected $viewName = "report-diginet-data";
    protected $maxH = 30 * 16;
    public function getSqlStr($params)
    {
        $sql = "SELECT dgnls.*
                FROM diginet_employee_leave_sheets dgnls
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
