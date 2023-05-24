<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\DB;

trait delete_TraitSQLDataSourceParamReport
{
    function getDataProdRouting()
    {
        dd(123);
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
    private function getOTUsers()
    {
        $sql = "SELECT DISTINCT(us.id) AS user_id, us.name
                FROM hr_overtime_request_lines otline, users us
                WHERE us.id = otline.user_id ORDER BY user_id";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    function sqlMultiSelectProdOrders($sql, $modeParams, $field = 'prod_order_id')
    {
        $x = $modeParams[$field];
        $con = str_replace(['[', ']'], ['(', ')'], json_encode($x));
        $sql .= "\n AND po.id IN " . $con;

        return $sql;
    }
}
