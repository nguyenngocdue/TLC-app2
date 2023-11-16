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
    }

    function getXAxis()
    {
        $result = Qaqc_insp_tmpl_sht::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            ->with(['getProdDiscipline'])
            ->orderBy('order_no')
            ->get();
        return $result;
    }

    function getYAxis()
    {
        $result = Qaqc_insp_chklst::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            ->orderBy('name')
            ->get();
        return $result;
    }

    function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            $item = (object)[
                'value' => $x->getProdDiscipline->description,
                'cell_class' => $x->getProdDiscipline->css_class,
            ];
            $result[$x->id] = $item;
        }
        return $result;
    }

    function getDataSource($xAxis, $yAxis)
    {
        $result = Qaqc_insp_chklst_sht::query()
            ->get();
        return $result;
    }
}
