<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\CurrentUser;

// class QaqcInspChklstShts extends MatrixForReportParent
// {
//     protected $dataIndexX = "qaqc_insp_tmpl_sht_id";
//     protected $dataIndexY = "qaqc_insp_chklst_id";
//     protected $rotate45Width = 200;
//     protected $rotate45Height = 150;

//     private $dataSource;

//     function __construct(
//         private $prodRoutingId = 49,
//         private $subProjectId = 107,
//         private $qaqcInspTmplId = 1007,
//         private $showOnlyInvolved = false,
//         private $nominatedListFn = "signature_qaqc_chklst_3rd_party" . "_list",
//     ) {
//         parent::__construct("qaqc_insp_chklst_shts");

//         $this->dataSource = $this->_getDataSource([], []);
//     }

//     function getXAxis()
//     {
//         $list = array_map(fn ($i) => $i->qaqc_insp_tmpl_sht_id, $this->dataSource);
//         $result = Qaqc_insp_tmpl_sht::query()
//             ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId);
//         //If client: show all, if inspectors: show involved chk sht
//         if ($this->showOnlyInvolved) $result = $result->whereIn('id', $list);
//         $result = $result->with(['getProdDiscipline'])
//             ->orderBy('order_no')
//             ->get();
//         return $result;
//     }

//     function getYAxis()
//     {
//         $result = Qaqc_insp_chklst::query()
//             ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId)
//             ->where('sub_project_id', $this->subProjectId)
//             ->orderBy('name')
//             ->get();
//         return $result;
//     }

//     function getXAxis2ndHeader($xAxis)
//     {
//         $result = [];
//         foreach ($xAxis as $x) {
//             $item = (object)[
//                 'value' => $x->getProdDiscipline->description,
//                 'cell_class' => $x->getProdDiscipline->css_class,
//             ];
//             $result[$x->id] = $item;
//         }
//         return $result;
//     }

//     function getLeftColumns($xAxis, $yAxis, $dataSource)
//     {
//         $result = parent::getLeftColumns($xAxis, $yAxis, $dataSource);

//         $columns = [];
//         if (CurrentUser::get()->isProjectClient()) $columns[] = ['dataIndex' => 'print', 'fixed' => 'left',];

//         return [...$result, ...$columns];
//     }

//     function attachMeta($xAxis, $yAxis, $dataSource)
//     {
//         $result = parent::attachMeta($xAxis, $yAxis, $dataSource);
//         foreach ($yAxis as $y) {
//             $result[$y->id]['print'] = (object)[
//                 'value' => "<i class='fa-duotone fa-print'></i>",
//                 'cell_href' => route("qaqc_insp_chklsts.show", $y->id),
//                 'cell_class' => "whitespace-nowrap text-center text-blue-500",
//             ];
//         }
//         return $result;
//     }

//     function _getDataSource($xAxis, $yAxis)
//     {
//         $db = Qaqc_insp_chklst_sht::query()
//             ->whereHas('getChklst', function ($q) {
//                 $q->where('sub_project_id', $this->subProjectId)
//                     ->where('prod_routing_id', $this->prodRoutingId)
//                     ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmplId);
//             })
//             ->get();
//         $result = [];
//         $cuid = CurrentUser::id();
//         if ($this->showOnlyInvolved) {
//             foreach ($db as $sheet) {
//                 $uids = $sheet->{$this->nominatedListFn}()->pluck('id')->toArray();
//                 if (in_array($cuid, $uids)) {
//                     $result[] = $sheet;
//                 }
//             }
//         } else {
//             foreach ($db as $sheet) {
//                 $result[] = $sheet;
//             }
//         }
//         // dd($result);
//         return $result;
//     }

//     function getDataSource($xAxis, $yAxis)
//     {
//         return $this->dataSource;
//     }
// }
