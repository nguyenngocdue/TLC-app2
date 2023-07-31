<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;


class ParamCheckSheetId extends ParentParamReports
{
    protected $referData = 'prod_order_id';
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                    chklst.id AS id
                    ,chklst.name AS name";
        if ($hasListenTo) $sql .= ",chklst.prod_order_id AS $this->referData";
        $sql .= ",chklst.qaqc_insp_tmpl_id AS chkls_qaqc_insp_tmpl_id
                    FROM qaqc_insp_chklsts chklst
                    WHERE chklst.deleted_at IS NULL
                    ORDER BY chklst.name
                ";
        $result = DB::select($sql);
        return $result;
    }
}
