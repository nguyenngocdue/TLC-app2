<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitSQLDataSourceParamReport
{
    function getDataProdRouting()
    {
        $sql = "
            SELECT
                prodr.id AS prod_routing_id
                ,prodr.name AS prod_routing_name
                FROM prod_orders prod, sub_projects sub, prod_routings prodr
                WHERE 1 = 1
                AND sub.id = prod.sub_project_id
                AND prodr.id = prod.prod_routing_id
                GROUP BY prod_routing_id,  prod_routing_name";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }
}
