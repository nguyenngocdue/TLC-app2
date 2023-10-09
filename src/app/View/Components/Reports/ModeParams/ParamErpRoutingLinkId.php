<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Erp_routing_link;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamErpRoutingLinkId extends ParentParamReports
{
    protected $referData = 'prod_routing_id';
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "
            SELECT 
            DISTINCT erpl.id AS id
            ,erpl.description
            ,erpl.name AS name
            ,erpl.prod_discipline_id AS prod_discipline_id";
        $sql .= "\n,prde.prod_routing_id AS prod_routing_id
                    FROM prod_routing_details prde, erp_routing_links erpl, prod_routings pr
                    WHERE 1 = 1
                    AND prde.deleted_at IS NULL
                    AND prde.erp_routing_link_id = erpl.id
                    AND prde.prod_routing_id = pr.id
                ORDER BY erpl.name
        ";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
