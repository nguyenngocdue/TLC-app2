<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_080_dataSource1 extends Controller
{
    private function getYears($params)
    {
        return (new Ghg_sheet_080_dataSource)->getYears($params);
    }

    public function getDataDepartedEmployees($params)
    {
        $years = $this->getYears($params);
        $strSQL1 = "SELECT \n";
        foreach ($years as $year) {
            $strSQL1 .= "
                            SUM(CASE WHEN  DATE_FORMAT(emsh.esg_month, '%Y-%m') <= '{$year}-12-31'
			                AND DATE_FORMAT(emsh.esg_month, '%Y') = {$year} THEN total ELSE 0 END) total_hours_worked_employee_by_year_{$year},

                            SUM(CASE WHEN  DATE_FORMAT(emsh.esg_month, '%Y-%m') < '{$year}-07-01'
			                AND DATE_FORMAT(emsh.esg_month, '%Y') = {$year} THEN total ELSE 0 END) first_range_hours_worked_employee_by_half_year_{$year},

                             SUM(CASE WHEN  DATE_FORMAT(emsh.esg_month, '%Y-%m') >= '{$year}-07-01'
			                AND DATE_FORMAT(emsh.esg_month, '%Y') = {$year} THEN total ELSE 0 END) second_range_hours_worked_employee_by_half_year_{$year},";
        };

        $strSQL = trim($strSQL1, ",");
        $strSQL .= "\n FROM esg_master_sheets emsh
                            WHERE emsh.esg_tmpl_id = 8";

        $dataSQL = (array)collect(DB::select($strSQL))->first();
        $dataSQL = ['hours_worked_employee' => $dataSQL];
        return $dataSQL;
    }
}
