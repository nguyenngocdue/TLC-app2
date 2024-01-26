<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamESGTmplId extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT
                        esgt.id AS id,
                        esgt.name AS name,
                        esgt.description AS description 
                        FROM esg_tmpls esgt
                        WHERE 1 = 1 
                        ORDER BY esgt.name";
                        
        $result = DB::select($sql);
        return $result;
    }
}
