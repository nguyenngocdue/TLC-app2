<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamMonth extends ParentTypeParamReport
{
    protected function getDataSource()
    {        
        $thisYear = date('Y');
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $dataSource = []; 
        
        for ($year = 2021; $year <= $thisYear; $year++) {
            foreach ($months as $month) {
                $dataSource[] = ['id' => $year . '-' . $month, 'name' => $year . '-' . $month];
            }
        }
        
        usort($dataSource, function ($a, $b) {
            return strtotime($b['id'] . '-01') - strtotime($a['id'] . '-01');
        });
        // dd($dataSource);
        return $dataSource;
    }
}
