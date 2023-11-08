<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Qaqc_wir;
use App\Models\Wir_description;

class QaqcWirs extends MatrixForReportParent
{
    protected $dataIndexX = "wir_description_id";
    protected $dataIndexY = "prod_order_id";

    function __construct(
        private $prodRoutingId = 49,
        private $subProjectId = 107,
    ) {
        parent::__construct("qaqc_wirs");
    }

    function getXAxis()
    {
        $result = Prod_routing::find($this->prodRoutingId)->getWirDescriptions()->pluck('id')->toArray();
        $result = Wir_description::whereIn('id', $result)
            ->orderBy('name')
            ->get();
        return $result;
    }

    function getYAxis()
    {
        $result = Prod_order::query()
            ->where('sub_project_id', $this->subProjectId)
            ->where('prod_routing_id', $this->prodRoutingId)
            ->orderBy('name')
            ->get();
        return $result;
    }

    function getDataSource($xAxis, $yAxis)
    {
        $result = Qaqc_wir::query()
            ->where('sub_project_id', $this->subProjectId)
            ->where('prod_routing_id', $this->prodRoutingId)
            ->get();
        return $result;
    }
}
