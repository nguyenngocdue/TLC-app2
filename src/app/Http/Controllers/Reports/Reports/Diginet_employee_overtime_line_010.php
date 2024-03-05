<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Diginet_employee_overtime_line_010 extends Report_ParentReport2Controller
{

    protected $viewName = "report-diginet-data";
    protected $maxH = 30;
    public function getSqlStr($params)
    {
        $sql = "SELECT dgnol.*
                FROM diginet_employee_overtime_lines dgnol
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
