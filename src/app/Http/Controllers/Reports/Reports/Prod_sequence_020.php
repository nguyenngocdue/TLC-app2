<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Utils\Support\DateReport;
use App\Utils\Support\SortData;
use Illuminate\Support\Facades\DB;

class Prod_sequence_020 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;
    use TraitLibPivotTableDataFields2;

    protected $mode = '020';
    protected $modeType = 'prod_sequence_020';
    protected $typeView = 'report-pivot';
    protected $pageLimit = 10;
    protected $tableTrueWidth = true;
    protected $maxH = 30;
    protected $showModeOnParam = true;

    private function filterProdOrdersNotProdRoutingLink($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);

        $sql = "SELECT 
                        tb1.*,
                        tb2.prod_routing_link_id,
                        tb2.prod_routing_link_name,
                        tb2.erp_routing_link_id,
                        tb2.erp_routing_link_name,
                        tb3.prod_sequence_id,
                        NULL AS man_power,
                        NULL AS hours,
                        NULL AS total_man_hours
                        FROM (SELECT
                                sp.project_id AS project_id,
                                pj.name AS project_name,
                                sp.id AS sub_project_id,
                                sp.name AS sub_project_name,
                                pr.id AS prod_routing_id,
                                pr.name AS prod_routing_name,
                                po.name AS prod_order_name,
                                po.id AS prod_order_id
                            FROM sub_projects sp
                            LEFT JOIN prod_orders po ON po.sub_project_id = sp.id
                            LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                            LEFT JOIN projects pj ON pj.id = sp.project_id 
                            WHERE 1 = 1
                                AND pj.id IS NOT NULL
                                #AND pr.id = 9
                                #AND sp.id = 21
                                #AND pj.id = 5
                                ";
        if ($pj = $valOfParams['project_id']) $sql .= "\n AND sp.project_id = $pj";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND po.sub_project_id = $sub";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";
        if ($po = $valOfParams['prod_order_id']) $sql .= "\n AND po.id IN($po)";


        $sql  .= " ) tb1
                        LEFT JOIN (
                                SELECT 
                                    pr.id AS prod_routing_id,
                                    pr.name AS prod_routing_name,
                                    prl.id AS prod_routing_link_id,
                                    prl.name AS prod_routing_link_name,
                                    erl.id AS erp_routing_link_id,
                                    erl.name AS erp_routing_link_name
                                    FROM prod_routings pr
                                    LEFT JOIN prod_routing_details prd ON prd.prod_routing_id = pr.id
                                    LEFT JOIN prod_routing_links prl ON prl.id = prd.prod_routing_link_id 
                                    LEFT JOIN erp_routing_links erl ON erl.id = prd.erp_routing_link_id 
                                    WHERE 1 = 1 ";

        if ($prl = $valOfParams['prod_routing_link_id']) $sql .= "\n AND prl.iid IN ($prl)";
        if ($pd = $valOfParams['prod_discipline_id']) $sql .= "\n AND prl.prod_discipline_id = $pd";
        if ($erp = $valOfParams['erp_routing_link_id']) $sql .= "\n AND prd.erp_routing_link_id IN ($erp)";


        $sql .= " \n ) tb2
                            ON tb1.prod_routing_id = tb2.prod_routing_id
                            LEFT JOIN (SELECT
                                    pse.id AS prod_sequence_id,
                                    pse.prod_order_id AS prod_order_id,
                                    pse.prod_routing_id AS prod_routing_id,
                                    pse.prod_routing_link_id AS prod_routing_link_id
                                    FROM prod_sequences pse ) tb3 ON tb1.prod_order_id = tb3.prod_order_id 
                                                            AND tb2.prod_routing_link_id = tb3.prod_routing_link_id
                                                            AND tb2.prod_routing_id = tb3.prod_routing_id
                            WHERE tb3.prod_sequence_id IS NULL
                                AND tb1.prod_order_id IS NOT NULL";
        // dd($sql);
        $data = DB::select($sql);
        return $data;
    }

    protected function getModeParamDataSource()
    {
        return [
            'report-prod_sequence_020' => 'Production Routing Links were created',
            'report-prod_sequence_070' => 'Production Routing Links weren\'t created',
        ];
    }

    // public function getTableColumns($params, $dataSource)
    // {
    //     if ($params['forward_to_mode'] === 'report-prod_sequence_070') {
    //         $modeType = 'prod_sequence_070';
    //         $tableColumnByModeType = $this->getTableColumnsFromManagePivot($modeType);
    //     } else {
    //         // $tableColumnByModeType = $this->getTableColumnsFromManagePivot($this->modeType);
    //     }
    //     return true;
    // }

    private function forwardToTargetMode($params)
    {
        if (isset($params['forward_to_mode'])) {
            $routeName = $params['forward_to_mode'];
            $primaryData = collect();
            switch ($routeName) {
                case 'report-prod_sequence_020':
                    $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
                    break;
                case 'report-prod_sequence_070':
                    $primaryData = (new Prod_sequence_070())->getDataSource($params);
                    break;
                case 'all':
                    $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
                    break;
                default:
                    dump("Check your route name");
            }
        }
        return $primaryData;
    }

    public function getDataSource($params)
    {
        $primaryData = $this->forwardToTargetMode($params);
        return collect($primaryData);
    }

    public function changeDataSource($dataSource, $params)
    {
        if (isset($params['forward_to_mode'])) {
            if ($params['forward_to_mode'] === 'report-prod_sequences') {
                foreach ($dataSource as &$values) {
                    $values->content_comment = str_replace(",", "<br/>", $values->content_comment);
                }
            }
            if ($params['forward_to_mode'] === 'all') {
                foreach ($dataSource as &$values) {
                    $values->content_comment = str_replace(",", "<br/>", $values->content_comment);
                }
                $dataSource = $dataSource->toArray();
                $prodOrdersNotRoutingLink = $this->filterProdOrdersNotProdRoutingLink($params);
                $dataSource = array_merge($dataSource, $prodOrdersNotRoutingLink);
                $dataSource = SortData::sortArrayByKeys($dataSource, ['sub_project_name', 'prod_order_name']);
            }
        }
        return collect($dataSource);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['forward_to_mode'] = 'report-prod_sequence_020';
        $params['picker_date'] = DateReport::defaultPickerDate('-2 months');
        return $params;
    }
}
