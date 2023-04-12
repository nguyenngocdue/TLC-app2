<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Workplace;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamOtWorkplaceId extends ParentTypeParamReport
{
    protected function getDataSource()
    {

        $sql = "SELECT 
                    otr.id AS id,
                    wp.name AS name
                    FROM hr_overtime_requests otr, workplaces wp
                    WHERE otr.id = wp.id";
        $dataSource = DB::select($sql);
        return $dataSource;
    }
}
