<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamYear extends ParentParamReports
{
    protected function getDataSource()
    {
        $thisYear = date('Y', time());
        for ($i = 2021; $i <= $thisYear; $i++) $dataSource[] = ['id' => $i, 'name' => $i];
        // foreach ([2023,2024,2025 ] as $item) $dataSource[] = ['id' => $item, 'name' => $item];
        return $dataSource;
    }
}