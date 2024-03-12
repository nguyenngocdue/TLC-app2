<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_020 extends Qaqc_wir_010
{

    protected $mode = '020';
    protected $viewName = 'document-wir-020';

    public function getSqlStr($params)
    {
        $dateIndex = $params['date_index'];
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
                        JOIN ( SELECT mtm.doc_id
                                FROM many_to_many mtm
                                WHERE mtm.doc_type = 'App\\\Models\\\Prod_routing'
                                AND mtm.term_id = 346
                            ) standarPr ON tb1.prod_routing_id IN (standarPr.doc_id) 
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

    private function getWeightOfWirDescriptionByRouting()
    {
        $sql = "SELECT 
                mtm.term_id AS mtm_prod_routing_id,
                mtm.doc_id AS mtm_doc_id,
                wirdes.wir_weight AS mtm_wir_weight
                #wirdes.name AS wirdes_name
                FROM many_to_many mtm, wir_descriptions wirdes
                WHERE mtm.doc_type = 'App\\\Models\\\Wir_description'
                AND mtm.term_type = 'App\\\Models\\\Prod_routing'
                AND wirdes.id = mtm.doc_id
                #AND mtm.term_id = 49
                #ORDER BY wirdes_name
                ";
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
                $values->previous_acceptance_percent = number_format($dataByDate['previous_date'][$subProjectId][$prodRoutingId]['avg_progress'], 2);
            }
            if (isset($dataByDate['latest_date'][$subProjectId][$prodRoutingId]['avg_progress'])) {
                $values->latest_acceptance_percent = number_format($dataByDate['latest_date'][$subProjectId][$prodRoutingId]['avg_progress'], 2);
            }
        }
        // dd($dataSourceQaqcWir010);
        return $dataSourceQaqcWir010;
    }



    public function changeDataSource($dataSource, $params)
    {
        $dataByDate = [];
        foreach ($dataSource as $key => $data) {
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

        $dataQaqcWir010 = new Qaqc_wir_010();
        $dataSourceQaqcWir010  = $dataQaqcWir010->getDataSource($params);
        $dataSourceQaqcWir010 = $dataQaqcWir010->changeDataSource($dataSourceQaqcWir010, $params);

        $indexData = $this->indexPercentByWeight($dataSourceQaqcWir010, $dataByDate);
        return collect($indexData);
    }
}
