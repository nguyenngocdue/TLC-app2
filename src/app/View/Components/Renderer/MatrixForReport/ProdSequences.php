<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;

class ProdSequences extends MatrixForReportParent
{
    protected $dataIndexX = "prod_routing_link_id";
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 300;
    protected $rotate45Height = 250;
    protected $closedDateColumn = 'end_date';

    private $showInReportToc = 365; // term_id of report_toc

    function __construct(
        private $prodRoutingId = 49,
        private $subProjectId = 107,
        private $dateToCompare = null,
        private $sequenceModeId = 2,
        private $prodDisciplineId = null,
    ) {
        parent::__construct("prod_sequences", $dateToCompare);
        // echo ($prodRoutingId . " - " . $subProjectId);
    }

    function getXAxis()
    {
        $result = Prod_routing::query()
            ->with(['getProdRoutingDetails' => function ($q) {
                $q->with(['getProdRoutingLink' => function ($q) {
                    $q->with('getDiscipline');
                }]);
            }])
            ->find($this->prodRoutingId);
        $allRoutingLinkDetails = $result->getProdRoutingDetails;
        // dump($allRoutingLinkDetails[0]);
        $allRoutingLinkDetails = $allRoutingLinkDetails->sortBy("order_no");
        $allRoutingLinks = $allRoutingLinkDetails->map(fn ($item) => $item->getProdRoutingLink);
        // dump($allRoutingLinks[0]);
        if ($this->prodDisciplineId) {
            $allRoutingLinks = $allRoutingLinks->filter(fn ($item) => ($item->prod_discipline_id == $this->prodDisciplineId));
        }

        $toHide = config('prod_discipline.to_hide');
        $result = [];
        switch ($this->sequenceModeId) {
            case 2: // All sequences
                return $allRoutingLinks->filter(fn ($i) => !in_array($i->prod_discipline_id, $toHide));
            case 3: // PPR-MEPF
                return $allRoutingLinks->filter(fn ($i) => in_array($i->prod_discipline_id, $toHide));
            case 1: // Major sequences
            default:
                foreach ($allRoutingLinks as $routingLink) {
                    $showMeOnIds = $routingLink->getScreensShowMeOn()->pluck('id')->toArray();
                    if (in_array($this->showInReportToc, $showMeOnIds)) {
                        $result[] = $routingLink;
                    }
                }
                return collect($result);
        }
        return collect($result);
    }

    function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            $result[$x->id] = (object)[
                'value' => $x->getDiscipline->description,
                'cell_class' => $x->getDiscipline->css_class,
            ];
        }
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
        $routingLinkIds = $xAxis->pluck('id')->toArray();
        $result = Prod_sequence::query()
            ->where('sub_project_id', $this->subProjectId)
            ->where('prod_routing_id', $this->prodRoutingId)
            ->whereIn('prod_routing_link_id', $routingLinkIds)
            ->get();
        // dump(sizeof($result));
        return $result;
    }

    function getLeftColumns($xAxis, $yAxis, $dataSource)
    {
        $result = parent::getLeftColumns($xAxis, $yAxis, $dataSource);
        $columns = [
            ['dataIndex' => 'production_name', 'fixed' => 'left',],
            ['dataIndex' => 'quantity', "title" => "QTY", 'fixed' => 'left', 'width' => 100],
            ['dataIndex' => 'progress', 'title' => 'Progress (%)',  'fixed' => 'left',],
        ];

        return [...$result, ...$columns];
    }

    function attachMeta($xAxis, $yAxis, $dataSource)
    {
        $result = parent::attachMeta($xAxis, $yAxis, $dataSource);
        foreach ($yAxis as $y) {
            $result[$y->id]['production_name'] = (object)[
                'value' => $y->production_name,
                'cell_class' => "whitespace-nowrap",
            ];
            $result[$y->id]['quantity'] = (object)[
                'value' => $y->quantity,
                'cell_class' => "whitespace-nowrap text-right",
            ];

            $result[$y->id]['progress'] = ($dataSource[$y->id]['progress'] ?? "N/A");

            // $result[$y->id]['progress'] = (object)[
            //     'value' => number_format($y->prod_sequence_progress, 2),
            //     'cell_class' => "whitespace-nowrap text-right",
            // ];
        }
        return $result;
    }

    function getCsvExportParams()
    {
        return [
            'prodRoutingId' => $this->prodRoutingId,
            'subProjectId' => $this->subProjectId,
            'dateToCompare' => $this->dateToCompare,
            'sequenceModeId' => $this->sequenceModeId,
            'prodDisciplineId' => $this->prodDisciplineId,
        ];
    }
}
