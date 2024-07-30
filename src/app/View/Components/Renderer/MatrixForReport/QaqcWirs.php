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
        private $dateToCompare = null,
    ) {
        parent::__construct("qaqc_wirs", $this->dateToCompare);
        // echo ($prodRoutingId . " - " . $subProjectId);        
    }

    function getXAxis()
    {
        $result = Prod_routing::find($this->prodRoutingId)->getWirDescriptions->pluck('id')->toArray();
        $result = Wir_description::whereIn('id', $result)
            ->orderBy('name')
            ->get();
        return $result;
    }

    function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            $result[$x->id] = (object)[
                'value' => $x->getProdDiscipline->description,
                'cell_class' => $x->getProdDiscipline->css_class,
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
        $result = Qaqc_wir::query()
            ->where('sub_project_id', $this->subProjectId)
            ->where('prod_routing_id', $this->prodRoutingId)
            ->get();
        return $result;
    }

    function getLeftColumns($xAxis, $yAxis, $dataSource)
    {
        $result = parent::getLeftColumns($xAxis, $yAxis, $dataSource);
        $columns = [
            ['dataIndex' => 'quantity', "title" => "QTY", 'fixed' => 'left',],
            ['dataIndex' => 'production_name', 'fixed' => 'left',],
            ['dataIndex' => 'progress', 'fixed' => 'left',],
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
        }
        return $result;
    }

    function getCsvExportParams()
    {
        return [
            'prodRoutingId' => $this->prodRoutingId,
            'subProjectId' => $this->subProjectId,
            'dateToCompare' => $this->dateToCompare,
            // 'sequenceModeId' => $this->sequenceModeId,
            // 'prodDisciplineId' => $this->prodDisciplineId,
        ];
    }
}
