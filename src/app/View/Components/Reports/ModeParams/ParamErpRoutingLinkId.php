<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Erp_routing_link;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamErpRoutingLinkId extends ParentParamReports
{
    protected $referData = 'prod_discipline_id';
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        $sql = "SELECT 
                        erpl.id AS id
                        ,erpl.description
                        ,erpl.name AS name";
        if ($hasListenTo) $sql .= ",erpl.prod_discipline_id AS prod_discipline_id";
        $sql .="\n FROM erp_routing_links erpl
                        WHERE erpl.deleted_at IS NULL
                        ORDER BY erpl.name";
        $result = DB::select($sql);
        // dump($result);
        return $result;
    }
}
