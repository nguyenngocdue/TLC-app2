<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Utils\Support\DateReport;


class Prod_sequence_020 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    protected $mode='020';
    protected $modeType = 'prod_sequence_020';
    protected $typeView = 'report-pivot';
    protected $pageLimit = 10;
    protected $tableTrueWidth = true;
    protected $maxH = 30;

    public function getDataSource($params)
    {
        $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
        return collect($primaryData);
    }

    public function changeDataSource($dataSource, $params)
    {
        foreach ($dataSource as &$values) {
            $values->content_comment = str_replace(",", "<br/>", $values->content_comment);
        }
        return $dataSource;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate('-2 months');
        // $params['project_id'] = 107;
        return $params;
    }
}
