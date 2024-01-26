<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamESGSheetId extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT
                        esgs.id AS id,
                        esgs.name AS name,
                        esgs.description AS description 
                        FROM esg_sheets esgs
                        WHERE 1 = 1
                        ORDER BY esgs.name";
        $result = DB::select($sql);
        return $result;
    }
}
