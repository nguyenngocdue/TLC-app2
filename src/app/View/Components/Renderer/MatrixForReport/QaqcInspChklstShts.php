<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShts extends MatrixForReportParent
{
    protected $dataIndexX = "qaqc_insp_tmpl_sht_id";
    protected $dataIndexY = "qaqc_insp_chklst_id";

    private $dataSource;

    function __construct(
        private $prodRoutingId = 49,
        private $subProjectId = 107,
        private $qaqcInspTmplId = 1007,
    ) {
        parent::__construct("qaqc_insp_chklst_shts");

        $this->dataSource = $this->_getDataSource([], []);
    }

    function getXAxis()
    {
        $list = array_map(fn ($i) => $i->qaqc_insp_tmpl_sht_id, $this->dataSource);
        $result = Qaqc_insp_tmpl_sht::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            ->whereIn('id', $list)
            ->with(['getProdDiscipline'])
            ->orderBy('order_no')
            ->get();
        return $result;
    }

    function getYAxis()
    {
        $result = Qaqc_insp_chklst::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
            ->where('sub_project_id', $this->subProjectId)
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

    function _getDataSource($xAxis, $yAxis)
    {
        $db = Qaqc_insp_chklst_sht::query()
            ->whereHas('getChklst', function ($q) {
                $q->where('sub_project_id', $this->subProjectId)
                    ->where('prod_routing_id', $this->prodRoutingId)
                    ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId);
            })
            ->get();
        $result = [];
        $cuid = CurrentUser::id();
        foreach ($db as $sheet) {
            $uids = $sheet->getMonitors1()->pluck('id')->toArray();
            if (in_array($cuid, $uids)) {
                $result[] = $sheet;
            }
        }
        // dump($result);
        return $result;
    }

    function getDataSource($xAxis, $yAxis)
    {
        return $this->dataSource;
    }
}
