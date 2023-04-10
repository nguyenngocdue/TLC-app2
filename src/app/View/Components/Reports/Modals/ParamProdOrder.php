<?php

namespace App\View\Components\Reports\Modals;


use App\View\Components\Reports\ParentIdReports;
use Illuminate\Support\Facades\DB;

class ParamProdOrder extends ParentIdReports
{
    protected $referData = 'sub_project';
    protected function getDataSource($attr_name)
    {
        $sql = "SELECT 
                    po.id AS id
                    ,po.name AS name
                    ,po.status AS po_status
                    ,po.sub_project_id AS $attr_name
                    ,po.prod_routing_id AS po_prod_routing_id
                    FROM prod_orders po
                    ORDER BY po.name
                ";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
