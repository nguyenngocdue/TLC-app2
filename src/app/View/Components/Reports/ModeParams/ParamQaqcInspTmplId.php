<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Qaqc_insp_tmpl;
use App\View\Components\Reports\ParentParamReports;


class ParamQaqcInspTmplId extends ParentParamReports
{
    protected $referData = 'prod_routing_id';
    protected function getDataSource()
    {
        $ids = Qaqc_insp_tmpl::all()->pluck('id')->toArray();
        $result = [];
        foreach ($ids as $id) {
            $ins = Qaqc_insp_tmpl::find($id);
            $prodRoutingIds = $ins->getProdRoutingsOfInspTmpl()->pluck('id')->toArray();
            $name = $ins->toArray()['name'];
            $result[] = [
                'id' => $id,
                'name' => $name,
                'prod_routing_id' => $prodRoutingIds
            ];
        }
        return $result;
    }
}
