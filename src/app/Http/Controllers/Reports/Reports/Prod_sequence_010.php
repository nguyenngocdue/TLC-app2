<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DateReport;

class Prod_sequence_010 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $mode = '010';
    protected $modeType = 'prod_sequence_010';
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = true;
    protected $maxH = 50 * 16;

    public function getDataSource($params)
    {
        $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
        // dump($primaryData, $params);
        return collect($primaryData);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] = DateReport::defaultPickerDate('-3 months');
        return $params;
    }
}
