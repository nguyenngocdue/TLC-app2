<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Prod_routing;
use App\View\Components\Reports\ParentIdParamReports;
use Illuminate\Support\Facades\DB;

class ParamProdRoutingId extends ParentIdParamReports
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
                    AND sp.deleted_by IS NULL
                    AND po.deleted_by IS NULL
                    AND pr.deleted_by IS NULL
                    AND sp.id = po.sub_project_id
                    AND po.prod_routing_id = pr.id
                GROUP BY pr.id, pr.name, sp.id
                ORDER BY name ";
        $result = DB::select($sql);
        // foreach ($result as &$line) {
        //     $line->checklist_type_parent_ids = Prod_routing::find($line->id)->getChklstTmpls()->pluck('id')->toArray();
        // }
        // $result = [
        //     ['id' => 1, 'name' => 'Testing V1', $attr_name => [7, 8, 9, 21]],
        //     ['id' => 2, 'name' => 'Testing V2', $attr_name => [21, 82]],
        // ];
        // dump($result);
        return $result;
    }
}
