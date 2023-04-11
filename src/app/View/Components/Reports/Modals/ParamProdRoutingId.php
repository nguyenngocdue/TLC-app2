<?php

namespace App\View\Components\Reports\Modals;


use App\View\Components\Reports\ParentIdReports;
use Illuminate\Support\Facades\DB;

class ParamProdRoutingId extends ParentIdReports
{
    protected $referData = 'sub_project_id';
    protected function getDataSource($attr_name)
    {
        // dump($attr_name);
        $sql = "SELECT
                pr.id AS id,
                pr.name AS name,
                sp.id AS $attr_name
                FROM sub_projects sp, prod_orders po, prod_routings pr
                WHERE 1 = 1
                AND sp.id = po.sub_project_id
                AND po.prod_routing_id = pr.id
                GROUP BY pr.id, pr.name, sp.id
                ORDER BY name ";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
