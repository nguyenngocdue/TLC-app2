<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamProdOrderId extends ParentParamReports
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'prod_routing_id';
    protected function getDataSource()
    {
        // dump($attr_name);
        $sql = "SELECT 
                    po.id AS id
                    ,po.name AS name
                    ,po.status AS po_status
                    ,po.sub_project_id AS sub_project_id
                    ,po.prod_routing_id AS prod_routing_id
                    FROM prod_orders po
                    WHERE po.deleted_at IS NULL
                    ORDER BY po.name
                ";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
