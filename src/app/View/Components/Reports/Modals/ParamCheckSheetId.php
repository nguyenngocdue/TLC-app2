<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentIdParamReports;
use App\View\Components\Reports\ParentIdReports;
use Illuminate\Support\Facades\DB;


class ParamCheckSheetId extends ParentIdParamReports
{
    protected $referData = 'prod_order_id';
    protected function getDataSource($attr_name)
    {
        $sql = "SELECT 
                    chklst.id AS id
                    ,chklst.name AS name
                    ,chklst.prod_order_id AS $attr_name
                    ,chklst.qaqc_insp_tmpl_id AS chkls_qaqc_insp_tmpl_id
                    FROM qaqc_insp_chklsts chklst
                    ORDER BY chklst.name
                ";
        $result = DB::select($sql);
        return $result;
    }
}
