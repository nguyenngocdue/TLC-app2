<?php

namespace App\Http\Controllers\Reports\Registers;

use App\BigThink\HasStatus;
use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLegendReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;

class Qaqc_insp_chklst_020 extends Report_ParentRegisterController

{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;
    use HasStatus;
    use TraitLegendReport;
    protected $rotate45Width = 300;
    protected  $sub_project_id = 82;
    protected  $prod_routing_id = 6;
    protected $mode = '020';
    protected $checksheet_type_id = 1;
    protected $maxH = 45;

    public function getSqlStr($params)
    {
        // dd($params);
        $sql = "SELECT 
        po_tb.*
        ,tmplsh.description AS tmplsh_des
        ,tmplsh.order_no AS tmplsh_order_no
        ,tmpl.description AS tmpl_chklst_desc
        ,tmpl.id AS tmpl_chklst_id
        ,chklst_shts.id AS chklst_shts_id
        ,chklst_shts.qaqc_insp_chklst_id AS chklst_shts_chklst_id
        ,chklst_shts.name AS chklst_shts_name
        ,chklst.id AS chklst_id
        ,chklst.progress AS chklst_progress
        ,chklst_shts.progress AS chklst_shts_progress
        ,chklst_shts.status AS chklst_shts_status

        FROM (SELECT
        pj.id AS project_id
        ,pj.name AS project_name
        ,sp.id AS sub_project_id
        ,sp.name AS sub_project_name
        ,po.id AS prod_order_id
        ,po.name AS prod_order_name
        ,pr.id AS prod_routing_id
        ,po.compliance_name AS prod_compliance_name
        ,pr.name AS prod_routing_name
        FROM projects pj, sub_projects sp, prod_orders po, prod_routings pr
        WHERE 1 = 1
            AND pj.deleted_at IS NULL
            AND sp.deleted_at IS  NULL
            AND po.deleted_at IS NULL
            AND pr.deleted_at IS NULL
            AND pj.id = sp.project_id
            AND po.sub_project_id = sp.id
            AND pr.id = po.prod_routing_id";
        if (isset($params['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}'";
        if (isset($params['prod_routing_id'])) $sql .= "\n AND pr.id = '{{prod_routing_id}}'";

        if (isset($params['prod_order_id2']) && is_array($x = $params['prod_order_id2'])) {
            $con = str_replace(['[', ']'], ['(', ')'], json_encode($x));
            $sql .= "\n AND po.id IN " . $con;
        }
        $sql .= "\n ) AS po_tb
              LEFT JOIN qaqc_insp_chklsts chklst ON chklst.prod_order_id = po_tb.prod_order_id ";
        if (isset($params['checksheet_type_id'])) $sql .= " AND chklst.qaqc_insp_tmpl_id = '{{checksheet_type_id}}'";
        $sql .= "\n LEFT JOIN qaqc_insp_chklst_shts chklst_shts ON chklst.id = chklst_shts.qaqc_insp_chklst_id
              LEFT JOIN qaqc_insp_tmpls tmpl ON tmpl.id = chklst.qaqc_insp_tmpl_id
              LEFT JOIN qaqc_insp_tmpl_shts tmplsh ON tmplsh.id =  chklst_shts.qaqc_insp_tmpl_sht_id AND tmpl.id = tmplsh.qaqc_insp_tmpl_id
              ORDER BY prod_order_name ";

        // dd($sql);
        return $sql;
    }
    public function getTableColumns($dataSource, $params)
    {
        $dataColumn1 = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
                "width" => 60,
            ],
            [
                "title" => "Prod Order Name",
                "dataIndex" => "prod_order_name",
                "align" => "center",
                "width" => 60,
            ],
            [
                "title" => "Compliance Name",
                "dataIndex" => "prod_compliance_name",
                // "align" => "center",
                "width" => 60,
            ],
            [
                "title" => "Action",
                "dataIndex" => "check_list",
                "align" => "center",
                "width" => 30,
            ],
            [
                "title" => "Progress (%)",
                "dataIndex" => "chklst_progress",
                "align" => "right",
                "width" => 30,
            ],
        ];

        $sheetsDesc = $this->transformSheetsDesc($params);
        $dataColumn2 = [];
        foreach (array_keys($sheetsDesc) as $value) {
            $dataColumn2[] = [
                "dataIndex" => $value,
                "align" => "center",
                "width" => 40,
            ];
        }
        $dataColumn = array_merge($dataColumn1, $dataColumn2);
        return $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Checklist Type',
                'dataIndex' => 'checksheet_type_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
                'allowClear' => true,
                'hasListenTo' => true,

            ],
            [
                'title' => 'Prod Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true,
                'multiple' => true,
                'hasListenTo' => true,
            ],

        ];
    }

    protected function tableDataHeader($params, $dataSource)
    {
        $sheets = $this->transformSheetsDesc($params);
        $dataHeader = [];
        foreach ($sheets as $key => $id) {
            $users = Qaqc_insp_tmpl_sht::find($id)->getMonitors1()->pluck('name', 'id')->toArray();
            $lenUsers = count($users);
            $icons = $lenUsers ? str_repeat("<i class='fa-duotone fa-user'></i>", $lenUsers) : "";
            $dataHeader[$key] = $icons;
        }
        // dump($dataHeader);
        return $dataHeader;
    }

    protected function getColorLegends()
    {
        $plural = 'qaqc_insp_chklst_shts';
        $statuses = LibStatuses::getFor($plural);
        $legendData = [
            'legend_title' => 'Status Icon Legend',
            'legend_col' => 8,
            'dataSource' => $statuses
        ];
        return $this->createLegendData($legendData);
    }

    protected function filterSheetFromProdRouting($params)
    {
        $checksheet_type_id = $params['checksheet_type_id'] ?? $this->checksheet_type_id;
        $sheets = Qaqc_insp_tmpl::find($checksheet_type_id)->getSheets;
        return $sheets->sortBy('order_no')->pluck('name', 'id')->toArray();
    }

    private function transformSheetsDesc($params)
    {
        $sheets = $this->filterSheetFromProdRouting($params);
        // $sheetsDesc = array_merge(...array_map(fn ($item) => [Report::slugName($item) => null], $sheets));
        $sheetsDesc = [];
        foreach ($sheets as $key => $name) {
            $sheetsDesc[Report::slugName($name)] = $key;
        }
        return $sheetsDesc;
    }


    protected function transformDataSource($dataSource, $params)
    {
        $items = $dataSource->toArray();
        $prodOderIds = $params['prod_order_id2'] ?? [0];
        $chklsts = Qaqc_insp_chklst::whereIn('prod_order_id', $prodOderIds)->pluck('qaqc_insp_tmpl_id', 'prod_order_id')->ToArray();
        $chklstType = $params['checksheet_type_id'] ?? '';
        $delProdOrder = [];
        foreach ($chklsts as $poId => $idChklstType) {
            if ($chklstType * 1 !== $idChklstType * 1) {
                $delProdOrder[] = $poId;
            }
        }

        $sheetsDesc = $this->transformSheetsDesc($params);
        $transformData = array_map(function ($item) {
            if (!is_null($item->chklst_shts_name)) {
                $sheetNameKey = Report::slugName($item->chklst_shts_name);
                return (array)$item + [$sheetNameKey => [
                    'status' => $item->chklst_shts_status,
                    'chklst_shts_id' => $item->chklst_shts_id,
                    'chklst_shts_progress' => $item->chklst_shts_progress
                ]];
            }
            return (array)$item;
        }, $items);
        // dd($sheetsDesc);

        $groupedArray = Report::groupArrayByKey($transformData, 'prod_order_id');
        $dataSource = Report::mergeArrayValues($groupedArray);
        // dd($groupedArray[223]);

        array_walk($dataSource, function ($item, $key) use (&$dataSource, $sheetsDesc) {
            if (!is_null($item['chklst_shts_name'])) {
                $itemHasNotShts = array_diff_key($sheetsDesc, $item);
                $dataSource[$key] = $item + $itemHasNotShts;
            }
            $dataSource[$key] = $item + $sheetsDesc;
        });
        return collect($dataSource);
    }

    protected function changeValueData($dataSource, $params)
    {
        $plural = 'qaqc_insp_chklst_shts';
        $statuses = LibStatuses::getFor($plural);
        $items = $dataSource->toArray();
        $cuid = CurrentUser::id();
        $inspTmplId = $params['checksheet_type_id'];

        foreach ($items as $key => $value) {
            $idx = array_search("chklst_shts_status", array_keys($value)); //  specify start point to render items
            $rangeArray = array_slice($value, $idx + 1, count($value) - $idx, true);
            foreach ($rangeArray as $col => $valCol) {
                if (is_array($valCol) && isset($statuses[$valCol['status']])) {
                    $status = $statuses[$valCol['status']];
                    $bgColor = 'bg-' . $status['color'] . '-' . $status['color_index'];
                    $textColor = 'text-' . $status['color'] . '-' . (1000 - $status['color_index']);
                    $value[$col] = (object)[
                        'value' =>  $status["icon"] ?? '<i class="fa-duotone fa-square-question"></i>',
                        'cell_class' => "$bgColor $textColor",
                        'cell_title' => $status['title'] . ' (' . $valCol['chklst_shts_progress'] . '%)',
                        'cell_href' =>  route($plural . '.edit',  $valCol['chklst_shts_id']),
                    ];
                } else {
                    $value[$col] = (object)[
                        'value' =>  '<i class="fa-sharp fa-regular fa-circle"></i>',
                        'cell_class' => 'bg-gray-100 text-gray-300',
                        'cell_title' => 'Not Yet Started'
                    ];
                };
            }
            $items[$key] = $value;
            $items[$key]['sub_project_name'] = (object)[
                'value' => $value['sub_project_name'],
                'cell_title' => 'ID: ' . $value['sub_project_id']
            ];
            $items[$key]['prod_order_name'] = (object)[
                'value' => $value['prod_order_name'],
                'cell_title' => 'ID: ' . $value['prod_order_id']
            ];
            if (is_null($value['chklst_shts_id'])) {
                $prodOrder = $value['prod_order_id'];

                $items[$key]['check_list'] = (object)[
                    'value' => '<i class="fa-regular fa-circle-plus text-green-800"></i> ',
                    'cell_href' => 'javascript:create(' . $inspTmplId . ',' . $prodOrder . ',' . $cuid . ');',
                ];
            } else {
                $items[$key]['check_list'] = (object)[
                    'value' => '<i class="fa-solid fa-folder-open text-green-800"></i>',
                    'cell_href' => route($this->getType() . '.edit', $value['chklst_id']),
                ];
            }
        }
        // dd($items[40]);
        return collect($items);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        $z = 'checksheet_type_id';
        $params[$x] = $this->sub_project_id;
        $params[$y] = $this->prod_routing_id;
        $params[$z] = $this->checksheet_type_id;
        return $params;
    }

    protected function getJS()
    {
        return "<script>function create(inspTmplId, prodOrderId, ownerId){
            const url = '/api/v1/qaqc/clone_chklst_from_tmpl'
            
            console.log(inspTmplId, prodOrderId, ownerId)

                const data = {inspTmplId, ownerId, prodOrderId}
                toastr.info('Creating a new document by cloning a template...')
                $.ajax({
                    type: 'POST',
                    url,
                    data,
                    success: (response)=>{
                        // console.log(response)
                        const {href} =  response
                        toastr.success('Created successfully. Opening the new document...')
                        location.href = href
                    },
                    error: (response)=> {
                        console.log('Failed', response)
                    },
                })
        }</script>";
    }

    protected function modifyDataToExportCSV($dataSource)
    {
        $data = $dataSource->toArray();
        array_walk($data, function ($items, $key) use (&$data) {
            foreach ($items as $field => $value) {
                if (isset($value['status'])) {
                    $data[$key][$field] = Report::makeTitle($value['status']);
                } else {
                    $data[$key][$field] = $value;
                }
            };
            $data[$key]['check_list'] = $items['chklst_id'] ? 'Yes' : 'No';
        });
        return $data;
    }
}
