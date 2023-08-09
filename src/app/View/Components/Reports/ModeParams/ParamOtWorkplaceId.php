<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Workplace;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamOtWorkplaceId extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT 
                    otr.id AS id,
                    wp.name AS name
                    FROM hr_overtime_requests otr, workplaces wp
                    WHERE 1 = 1
                        AND otr.id = wp.id
                        AND otr.deleted_at IS NULL
                        AND wp.deleted_at IS NULL";
        $dataSource = DB::select($sql);
        return $dataSource;
    }
}
