<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\ParameterReport;
use App\Utils\Support\Report;
use App\Utils\Support\SortData;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_010 extends Qaqc_wir_dataSource
{

    protected $mode = '010';
    protected $viewName = 'document-qaqc-wir-010';

    public function getSqlStr($params)
    {
        $dateIndex = $params['date_index'];
        // dd($dateIndex);
        $sql = "SELECT tb3.*
                FROM(SELECT 
                            tb1.project_id,
                            tb1.project_name,
                            tb1.sub_project_id,
                            tb1.sub_project_name,
                            tb1.prod_routing_id,
                            tb1.prod_routing_name,
                            tb1.prod_order_id,
                            tb2.qaqcwir_status,
                            tb2.wir_description_id,
                            tb2.wir_weight
                        FROM (SELECT
                                sp.project_id AS project_id,
                                pj.name AS project_name,
                                sp.id AS sub_project_id,
                                sp.name AS sub_project_name,
                                pr.id AS prod_routing_id,
                                pr.name AS prod_routing_name,
                                po.id AS prod_order_id,  
                                po.name AS prod_order_name
                            FROM sub_projects sp
                            LEFT JOIN prod_orders po ON po.sub_project_id = sp.id
                            LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                            LEFT JOIN projects pj ON pj.id = sp.project_id
                            WHERE 1 = 1
                            #AND pr.id = 49
                            #AND sp.id = 107
                            #AND pj.id = 8 
                            #AND po.id = 1325
                            ) tb1
                        JOIN ( SELECT prdtso.prod_routing_id as doc_id
                                    FROM ym2m_prod_routing_term_show_me_on prdtso
                                    WHERE 1 = 1
                                    AND prdtso.term_id = 346
                            ) standardPr ON tb1.prod_routing_id IN (standardPr.doc_id) 
                        LEFT JOIN (SELECT
                            qaqcwir.sub_project_id AS sub_project_id,
                            qaqcwir.project_id AS project_id,
                            qaqcwir.prod_routing_id AS prod_routing_id,
                            qaqcwir.prod_order_id AS prod_order_id,
                            qaqcwir.wir_description_id AS wir_description_id,
                            wirdes.wir_weight AS wir_weight,
                            qaqcwir.status AS qaqcwir_status
                            FROM qaqc_wirs qaqcwir
                            LEFT JOIN wir_descriptions wirdes ON wirdes.id =  qaqcwir.wir_description_id
                            WHERE 1 = 1
                                AND SUBSTR(qaqcwir.closed_at, 1, 10) <= '$dateIndex' OR qaqcwir.closed_at IS NULL) AS tb2 ON tb2.sub_project_id = tb1.sub_project_id
                                AND tb2.project_id = tb1.project_id  AND tb2.prod_routing_id = tb1.prod_routing_id AND tb1.prod_order_id = tb2.prod_order_id) AS tb3
                        WHERE tb3.qaqcwir_status IN ('closed', 'finished', 'approved')";
        return $sql;
    }

    private function getProductionsData($params)
    {
        $finishedStr = str_replace(['[', ']'], ['', ''], json_encode(LibStatuses::$finishedArray));
        $naStr = str_replace(['[', ']'], ['', ''], json_encode(LibStatuses::$naArray));
        [$previousDate, $latestDate] = $this->getDate($params);
        $paramsFormat = ParameterReport::formatValueParams($params);

        $sqlStr = "SELECT 
                    tb1.*,
                    sp.name AS sub_project_name,
                    pr.name AS prod_routing_name,
                    IF(ROUND(count_prl_previous_finished * 100/ total_prod_routing_link, 2) = '0.00', NULL,
                    ROUND(count_prl_previous_finished * 100/ total_prod_routing_link, 2))
                     previous_finished_prod_percent,
                    IF(ROUND(count_prl_latest_finished * 100/ total_prod_routing_link, 2) = '0.00', NULL,
                        ROUND(count_prl_latest_finished * 100/ total_prod_routing_link, 2))
                        latest_finished_prod_percent,
                    COUNT(po.id) AS count_prod_orders
                    FROM(SELECT
                        sequ.sub_project_id AS sub_project_id,
                        sequ.prod_routing_id AS prod_routing_id,
                        COUNT(sequ.prod_routing_link_id) AS total_prod_routing_link,
                        SUM(CASE WHEN 
                            sequ.status IN ($finishedStr)
                            AND sequ.closed_at <= '$previousDate'
                                THEN 1 ELSE 0 END) count_prl_previous_finished,
                        SUM(CASE WHEN 
                            sequ.status IN ($finishedStr)
                            AND sequ.closed_at <= '$latestDate'
                                THEN 1 ELSE 0 END) count_prl_latest_finished
                        FROM prod_sequences sequ 
                        WHERE 1 = 1
                        AND sequ.status NOT IN ($naStr) ";

        if (Report::checkValueOfField($paramsFormat, 'prod_routing_id')) $sqlStr .= "\n AND sequ.prod_routing_id IN ({$paramsFormat['prod_routing_id']})";
        if (Report::checkValueOfField($paramsFormat, 'sub_project_id')) $sqlStr .= "\n AND sequ.sub_project_id IN ({$paramsFormat['sub_project_id']})";

        $sqlStr .= "\n GROUP BY sub_project_id, prod_routing_id ) tb1
                    LEFT JOIN sub_projects sp ON sp.id = tb1.sub_project_id AND sp.id IS NOT NULL
                    LEFT JOIN prod_routings pr ON pr.id = tb1.prod_routing_id
                    LEFT JOIN prod_orders po ON po.prod_routing_id = pr.id AND tb1.prod_routing_id = po.prod_routing_id
                    GROUP BY sub_project_id, prod_routing_id";
        $sqlData = DB::select($sqlStr);
        return collect($sqlData);
    }

    protected function basicInfoWidgetReport()
    {
        return [
            'key' => 'qaqc_wir_overall_complete_status',
            'title_report' => "QC Acceptance Report"
        ];
    }


    private function getWeightOfWirDescriptionByRouting()
    {
        $sql = "SELECT
                    prwd.prod_routing_id AS mtm_prod_routing_id,
                    prwd.wir_description_id AS mtm_doc_id,
                    wirdes.wir_weight AS mtm_wir_weight
                    FROM ym2m_prod_routing_wir_description prwd, wir_descriptions wirdes
                    WHERE 1 = 1
                    AND prwd.wir_description_id = wirdes.id";
        $sqlData = DB::select($sql);
        return collect($sqlData);
    }

    private function getNaWIRs($params)
    {
        $dateIndex = $params['date_index'];
        $sql = " SELECT 
                        qaqc_wir_prod_routing_id,
                        #qaqc_wir_project_id, qaqc_wir_sub_project_id, qaqc_wir_status, 
                        #qaqc_wir_id, qaqc_wir_name, 
                        qaqc_wir_prod_order_id, 
                        qaqc_wir_description_id
                    FROM (SELECT 
                    qaqcwir.id AS qaqc_wir_id,
                    qaqcwir.name AS qaqc_wir_name,
                    qaqcwir.prod_routing_id AS qaqc_wir_prod_routing_id,
                    qaqcwir.prod_order_id AS qaqc_wir_prod_order_id,
                    qaqcwir.project_id AS qaqc_wir_project_id,
                    qaqcwir.sub_project_id AS qaqc_wir_sub_project_id,
                    qaqcwir.status AS qaqc_wir_status,
                    qaqcwir.closed_at AS closed_at,
                    qaqcwir.wir_description_id AS qaqc_wir_description_id
                    FROM qaqc_wirs qaqcwir
                    WHERE 1 = 1
                    AND SUBSTR(qaqcwir.closed_at, 1, 10) <= '$dateIndex'
                    OR qaqcwir.closed_at IS NULL
                    AND qaqcwir.status = 'not_applicable') AS tb1
                    WHERE tb1.qaqc_wir_status = 'not_applicable'";
        $sqlData = DB::select($sql);
        return collect($sqlData);
    }

    private function generateDataSourceByDate($params)
    {
        [$previousDate, $latestDate] = $this->getDate($params);
        $params['date_index'] = $previousDate;

        $dataPreviousDate = $this->getSql($params);
        $dataPreviousDate = collect(DB::select($dataPreviousDate));
        $dataNaWIRsPreviousDate = $this->getNaWIRs($params);

        $params['date_index'] = $latestDate;
        $dataLastDate = $this->getSql($params);
        $dataLastDate = collect(DB::select($dataLastDate));
        $dataNaWIRsLastDate = $this->getNaWIRs($params);

        $output = [];
        $output['previous_date'] = [
            'wir_done' => $dataPreviousDate,
            'wir_na' => $dataNaWIRsPreviousDate
        ];
        $output['latest_date'] = [
            'wir_done' => $dataLastDate,
            'wir_na' => $dataNaWIRsLastDate
        ];
        return $output;
    }

    public function getDataSource($params)
    {
        $dataSource = $this->generateDataSourceByDate($params);
        $productions = $this->getProductionsData($params)->toArray();
        $dataSource = array_merge($dataSource, ['productions' => $productions]);
        return  $dataSource;
    }

    private function getDataWIRsDesToCalculate($groupWeightByProdRouting, $groupNaByProdOrder, $groupByProdOrderDone)
    {
        $result = [];
        foreach ($groupByProdOrderDone as $idSub => $values) {
            foreach ($values as $idPr => $prodOrders) {
                if (!isset($groupNaByProdOrder[$idPr])) {
                    $dataIndexWIRsDes = $groupWeightByProdRouting[$idPr];
                    $prodOrderIds = array_keys($prodOrders);
                    $prodOrderIdByWIRs = [];
                    foreach ($prodOrderIds as $id) $prodOrderIdByWIRs[$id] = $dataIndexWIRsDes;
                    $result[$idSub][$idPr] = $prodOrderIdByWIRs;
                } else {
                    $allWIRsDes = $groupWeightByProdRouting[$idPr];
                    foreach (array_keys($prodOrders) as $id) {
                        if (!isset($groupNaByProdOrder[$idPr][$id])) {
                            $result[$idSub][$idPr][$id] = $allWIRsDes;
                        } else {
                            $naWIRsId = array_column($groupNaByProdOrder[$idPr][$id], 'qaqc_wir_description_id');
                            $newWIRsDes = array_filter($allWIRsDes, function ($value) use ($naWIRsId) {
                                return !in_array($value["mtm_doc_id"], $naWIRsId,);
                            });
                            $result[$idSub][$idPr][$id] = $newWIRsDes;
                        }
                    }
                }
            }
        }
        return $result;
    }

    private function sumWeightOfWIRs($dataWIRsDesToCalculate)
    {
        $result = [];
        foreach ($dataWIRsDesToCalculate as $idSub => $values) {
            foreach ($values as $key => $prodOrders) {
                foreach ($prodOrders as $id => $prodOrder) {
                    // dd($prodOrder);
                    $sum = array_sum(array_column($prodOrder, 'mtm_wir_weight'));
                    $result[$idSub][$key][$id]['total_weight'] = $sum;
                    $result[$idSub][$key][$id]['count_all_wir_desc'] = count($prodOrder);
                }
            }
        }
        return $result;
    }

    private function calculatePercentOfProdOrders($prodOrderWIRsDone, $dataTotalWeightProdOrder)
    {
        $result = [];
        foreach ($prodOrderWIRsDone as $idSub => $values) {
            foreach ($values as $idPr => $prodOrders) {
                foreach ($prodOrders as $idPo => $prodOrder) {
                    $percent = 0;
                    $infoWeight = $dataTotalWeightProdOrder[$idSub][$idPr][$idPo];
                    if (!$infoWeight['total_weight']) {
                        $countDone = count($prodOrder);
                        $percent = round($countDone * 100 / $infoWeight['count_all_wir_desc'], 2);
                        $result[$idSub][$idPr][$idPo] = $percent;
                    } else {
                        foreach ($prodOrder as $value) {
                            $percent += ($value['wir_weight'] * 100) / $infoWeight['total_weight'];
                        }
                        $result[$idSub][$idPr][$idPo] = round($percent, 2);
                    }
                }
            }
        }
        // dd($result);
        return $result;
    }

    private function avgPercentEachRouting($data)
    {
        $result = [];
        foreach ($data as $idSub => $items) {
            foreach ($items as $key => $values) {
                $result[$idSub][$key] =  [
                    "sub_project_id" => $idSub,
                    "prod_routing_id" => $key,
                    "avg_progress" => round(array_sum($values) / count($values), 2)
                ];
            }
        }
        return $result;
    }

    private function indexPercentByWeight($dataSourceQaqcWir010, $dataByDate)
    {
        foreach ($dataSourceQaqcWir010 as &$values) {
            $subProjectId = $values->sub_project_id;
            $prodRoutingId = $values->prod_routing_id;
            if (isset($dataByDate['previous_date'][$subProjectId][$prodRoutingId]['avg_progress'])) {
                $values->previous_qaqc_percent = number_format($dataByDate['previous_date'][$subProjectId][$prodRoutingId]['avg_progress'], 2);
            }
            if (isset($dataByDate['latest_date'][$subProjectId][$prodRoutingId]['avg_progress'])) {
                $values->latest_qaqc_percent = number_format($dataByDate['latest_date'][$subProjectId][$prodRoutingId]['avg_progress'], 2);
            }
        }
        // dd($dataSourceQaqcWir010);
        return $dataSourceQaqcWir010;
    }

    private function filterNumberOfProdOrders()
    {
        $sql = " SELECT
                    pr.id AS id,
                    COUNT(po.id) count_prod_orders
                    FROM sub_projects sp, prod_orders po, prod_routings pr
                    WHERE 1 = 1
                    AND po.sub_project_id = sp.id
                    AND pr.id = po.prod_routing_id
                    GROUP BY pr.id";
        $sqlData = DB::select($sql);
        return collect($sqlData);
    }

    private function mapItemsFromQaqcAndProductions($qaqcData, $productionData)
    {
        // dd($qaqcData, $productionData);
        // Combine and deduplicate keys from both QAQC and production data.
        $idProdRoutings = array_merge(array_keys($qaqcData), array_keys($productionData));
        $idProdRoutings = array_merge(array_keys($qaqcData), array_keys($productionData));
        $result = [];
        foreach ($idProdRoutings as $prodRoutingId) {
            $items = [];
            if (isset($qaqcData[$prodRoutingId]) && isset($productionData[$prodRoutingId])) {
                $qaqcs = $qaqcData[$prodRoutingId];
                $prods = $productionData[$prodRoutingId];
                $items = array_merge($qaqcs, $prods);
            } else {
                if (isset($qaqcData[$prodRoutingId])) $items = $qaqcData[$prodRoutingId];
                else $items = $productionData[$prodRoutingId];
            }
            $groupBySubProject = Report::groupArrayByKey($items, 'sub_project_id');
            unset($groupBySubProject[""]);
            $valueMergedChildren = array_values(array_map(fn ($item) => array_merge(...$item), $groupBySubProject));
            $result = array_merge($result, $valueMergedChildren);
        }
        // set Status
        $result = array_map(function ($item) {
            if (!isset($item['percent_status']) || is_null($item['percent_status'])) {
                $item['percent_status'] = 'Not Yet';
            };
            return $item;
        }, $result);
        return $result;
    }


    public function changeDataSource($dataSource, $params)
    {

        $dataByDate = [];
        foreach ($dataSource as $key => $data) {
            if ($key !== 'previous_date' || $key !== 'latest_date') continue;
            $WIRsDone = $data['wir_done'];
            $WIRsNa = $data['wir_na'];

            $dataWIRsDone =  Report::getItemsFromDataSource($WIRsDone);
            $groupBySubProject = Report::groupArrayByKey($dataWIRsDone, 'sub_project_id');

            $groupByProdRouting = array_map(fn ($item) => Report::groupArrayByKey($item, 'prod_routing_id'), $groupBySubProject);
            $groupByProdOrderDone = array_map(fn ($item) => Report::groupArrayByKeyV3($item, 'prod_order_id'), $groupByProdRouting);

            // get Weight of All Wir_description
            $weightWIRs = $this->getWeightOfWirDescriptionByRouting();
            $weightWIRs =  Report::getItemsFromDataSource($weightWIRs);
            $groupWeightByProdRouting = Report::groupArrayByKey($weightWIRs, 'mtm_prod_routing_id');

            // get NA of WIRs
            $dataWIRsNa =  Report::getItemsFromDataSource($WIRsNa);
            $groupNaWIRsByProdRouting = Report::groupArrayByKey($dataWIRsNa, 'qaqc_wir_prod_routing_id');
            $groupNaByProdOrder = array_map(fn ($item) => Report::groupArrayByKey($item, 'qaqc_wir_prod_order_id'), $groupNaWIRsByProdRouting);

            //calculate Percentage of each Prod_Order
            $dataWIRsDesToCalculate = $this->getDataWIRsDesToCalculate($groupWeightByProdRouting, $groupNaByProdOrder, $groupByProdOrderDone);
            $dataTotalWeightProdOrder = $this->sumWeightOfWIRs($dataWIRsDesToCalculate);

            $dataPercentProdOrders = $this->calculatePercentOfProdOrders($groupByProdOrderDone, $dataTotalWeightProdOrder);

            $avgPercentEachRouting = $this->avgPercentEachRouting($dataPercentProdOrders);
            $dataByDate[$key] = $avgPercentEachRouting;
        }

        $dataQaqcWir010 = new Qaqc_wir_dataSource();
        $dataSourceQaqcWir010  = $dataQaqcWir010->getDataSource($params);
        $dataSourceQaqcWir010 = $dataQaqcWir010->changeDataSource($dataSourceQaqcWir010, $params);

        $indexData = $this->indexPercentByWeight($dataSourceQaqcWir010, $dataByDate)->toArray();

        $indexData = array_map(fn ($item) => (array)$item, $indexData);
        $groupProdRoutingsForQaqc = Report::groupArrayByKey($indexData, 'prod_routing_id');
        // dd($groupProdRoutingsForQaqc);

        // Productions
        $productionsData = $dataSource['productions'];
        $productionsData = array_map(fn ($item) => (array)$item, $productionsData);
        $groupProdRoutingsForProd = Report::groupArrayByKey($productionsData, 'prod_routing_id');

        $result = $this->mapItemsFromQaqcAndProductions($groupProdRoutingsForQaqc, $groupProdRoutingsForProd);
        //add number of prod_orders
        $numberOfProdOrders = $this->filterNumberOfProdOrders()->pluck('count_prod_orders', 'id')->toArray();
        $result = array_map(function ($item) use ($numberOfProdOrders) {
            $item['number_of_prod_orders'] = isset($numberOfProdOrders[$item['prod_routing_id']]) ? $numberOfProdOrders[$item['prod_routing_id']] : null;
            return $item;
        }, $result);

        $fields = ['sub_project_name', 'prod_routing_name'];
        $result = SortData::sortArrayByKeys($result, $fields);


        //add number of prod_orders
        $numberOfProdOrders = $this->filterNumberOfProdOrders()->pluck('count_prod_orders', 'id')->toArray();
        $result = array_map(function ($item) use ($numberOfProdOrders) {
            $item['number_of_prod_orders'] = isset($numberOfProdOrders[$item['prod_routing_id']]) ? $numberOfProdOrders[$item['prod_routing_id']] : null;
            return $item;
        }, $result);

        $apartments = $this->getApartmentsEachProdRouting($params);
        $groupProdRoutingsForApart = Report::groupArrayByKey($apartments, 'sub_project_id');
        $groupProdRoutingsForApart = array_map(fn ($item) => Report::groupArrayByKey($item, 'prod_routing_id'), $groupProdRoutingsForApart);

        // add apartments to datasource
        foreach ($result as $key => &$values) {
            $subProjectId = $values['sub_project_id'];
            $prodRoutingId = $values['prod_routing_id'];
            if (isset($groupProdRoutingsForApart[$subProjectId]) && isset($groupProdRoutingsForApart[$subProjectId][$prodRoutingId])) {
                $apartValues = current($groupProdRoutingsForApart[$subProjectId][$prodRoutingId]);
                $values['number_of_apartments'] = $apartValues['number_of_pj_units'];
            } else {
                $values['number_of_apartments'] = Null;
            }
        }
        return collect($result);
    }
}
