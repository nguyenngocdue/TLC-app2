<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_sht;

class QaqcInspChklstShts extends MatrixForReportParent
{
    protected $dataIndexX = "qaqc_insp_tmpl_sht_id";
    protected $dataIndexY = "qaqc_insp_chklst_id";

    function __construct(
        private $prodRoutingId = 49,
        private $subProjectId = 107,
        private $qaqcInspTmplId = 1007,
    ) {
        parent::__construct("qaqc_insp_chklst_shts");
        // echo ($prodRoutingId . " - " . $subProjectId);
    }

    function getXAxis()
    {
        $result = Qaqc_insp_tmpl_sht::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            ->orderBy('order_no')
            ->get();
        return $result;
    }

    function getYAxis()
    {
        $result = Qaqc_insp_chklst::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            // ->where('sub_project_id', $this->subProjectId)
            // ->where('prod_routing_id', $this->prodRoutingId)
            ->orderBy('name')
            ->get();
        return $result;
    }

    function getDataSource($xAxis, $yAxis)
    {
        $result = Qaqc_insp_chklst_sht::query()
            // ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            // ->where('sub_project_id', $this->subProjectId)
            // ->where('prod_routing_id', $this->prodRoutingId)
            ->get();
        return $result;
    }

    function getLeftColumns($xAxis, $yAxis, $dataSource)
    {
        $result = parent::getLeftColumns($xAxis, $yAxis, $dataSource);
        $columns = [
            ['dataIndex' => 'production_name'],
            ['dataIndex' => 'quantity'],
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
}
