<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamYear extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $thisYear = date('Y', time());
        for ($i = 2022; $i <= $thisYear; $i++) $dataSource[] = ['id' => $i, 'name' => $i];
        // foreach ([2023,2024,2025 ] as $item) $dataSource[] = ['id' => $item, 'name' => $item];
        return $dataSource;
    }
}
