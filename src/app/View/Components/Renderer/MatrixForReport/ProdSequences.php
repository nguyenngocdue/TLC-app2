<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;

class ProdSequences extends MatrixForReportParent
{
    protected $dataIndexX = "prod_routing_link_id";
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 200;
    protected $rotate45Height = 150;

    private $showInReportToc = 365; // term_id of report_toc

    function __construct(
        private $prodRoutingId = 49,
        private $subProjectId = 107,
    ) {
        parent::__construct("prod_sequences");
    }

    function getXAxis()
    {
        $result = Prod_routing::query()
            ->with('getProdRoutingLinks')
            ->find($this->prodRoutingId);
        $allRoutingLinks = $result->getProdRoutingLinks;

        $result = [];
        foreach ($allRoutingLinks as $routingLink) {
            $showMeOnIds = $routingLink->getScreensShowMeOn()->pluck('id')->toArray();
            if (in_array($this->showInReportToc, $showMeOnIds))
                $result[] = $routingLink;
        }

        return collect($result);
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

    function getDataSource()
    {
        $result = Prod_sequence::query()
            ->where('sub_project_id', $this->subProjectId)
            ->where('prod_routing_id', $this->prodRoutingId)
            ->get();
        return $result;
    }
}
