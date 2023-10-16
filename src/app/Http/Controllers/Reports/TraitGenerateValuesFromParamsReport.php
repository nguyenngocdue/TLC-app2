<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\DateReport;

trait TraitGenerateValuesFromParamsReport
{
    public function generateValuesFromParamsReport($params){
        $valOfParams = DateReport::createValueForParams([
            'sub_project_id',
            'project_id',
            'prod_routing_id',
            'prod_order_id',
            'prod_routing_link_id',
            'erp_routing_link_id',
            'prod_discipline_id',
            'picker_date',
            'status',
        ], $params);
        return $valOfParams;
    }
}
