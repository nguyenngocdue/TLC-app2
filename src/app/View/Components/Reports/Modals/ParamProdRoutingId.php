<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Prod_routing;
use App\View\Components\Reports\ParentIdParamReports;
use App\View\Components\Reports\ParentIdParamReports2;
use Illuminate\Support\Facades\DB;

class ParamProdRoutingId extends ParentIdParamReports2
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'checksheet_type_id';
    protected function getDataSource($attr_name)
    {
        // dump($attr_name);
        $sql = "SELECT
                pr.id AS id,
                pr.name AS name,
                GROUP_CONCAT(DISTINCT(sp.id)) AS sub_project_id
                FROM sub_projects sp, prod_orders po, prod_routings pr
                WHERE 1 = 1
                    AND sp.deleted_by IS NULL
                    AND po.deleted_by IS NULL
                    AND pr.deleted_by IS NULL
                    AND sp.id = po.sub_project_id
                    AND po.prod_routing_id = pr.id
                GROUP BY pr.name, id
                ORDER BY id ";
        $result = DB::select($sql);

        $subProjectKeyName = $this->referData;
        $qaqcTmplKeyName = $this->referData1;
        foreach ($result as &$line) {
            $sup_project_ids = array_map('intval',explode(",",$line->sub_project_id));
            $line->$subProjectKeyName= $sup_project_ids;
            $line->$qaqcTmplKeyName= Prod_routing::find($line->id)->getChklstTmpls()->pluck('id')->toArray();
        }
        // $result = [
        //     ['id' => 1, 'name' => 'Testing V1', $this->referData => [1,2,3,8,9,21,61], $qaqcTmplKey => [1,5,6,7,8,9,1,61,20,9]],
        //     ['id' => 2, 'name' => 'Testing V2', $this->referData =>[61,21, 82], $qaqcTmplKey=> [1,2,5,7,8],]
        // ];
        // dump($result);
        return $result;
    }
}