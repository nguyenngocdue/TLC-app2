<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamYear extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        foreach ([2020,2021,2022,2023,2024,2025 ] as $item) $dataSource[] = ['id' => $item, 'name' => $item];
        return $dataSource;
    }
}
