<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamQaqcInspTmplId extends ParentParamReports
{
    protected $referData = 'prod_routing_id';
    protected function getDataSource()
    {
        $sql = "SELECT 
                    DISTINCT tb1.term_id AS prod_routing_id,
                    qaqcitmpl.name AS name,
                    tb1.doc_id AS id
                FROM (SELECT *  
                FROM many_to_many mtm 
                WHERE 1 = 1 
                AND mtm.doc_type = 'App\\\Models\\\qaqc_insp_tmpl' ) AS tb1
                LEFT JOIN prod_routings pr ON pr.id = tb1.term_id
                LEFT JOIN qaqc_insp_tmpls qaqcitmpl ON qaqcitmpl.id = tb1.doc_id ";
        $result = DB::select($sql);
        return $result;
    }
}
