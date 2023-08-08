<?php

namespace App\Warehouse;



class Wh_report_data_1s extends Wh_parent
{
    protected $tableName = 'wh_report_data_1s';
    public function getSqlStr($userIds, $month)
    {
        $sql = "WITH 
                        t1 AS (SELECT
                            wir.prod_routing_id AS prod_routing_id
                            ,wir.status AS status
                            ,wir.prod_order_id AS wir_prod_order_id
                            ,wir.wir_description_id AS wir_description_id
                            ,wir.closed_at AS wir_closed_at
                            ,wir.project_id AS wir_project_id
                            ,wir.sub_project_id AS wir_sub_project_id
                            FROM qaqc_wirs wir
                            WHERE 1 = 1
                            #AND wir.project_id = 5
                            #AND wir.sub_project_id = 21
                            )
                        ,t2 AS (
                            SELECT
                                mtm.term_id AS mtm_prod_routing_id,
                                COUNT(mtm.doc_id) AS count_wir_desc
                            FROM
                                many_to_many mtm
                            WHERE 1 = 1
                                AND mtm.doc_type = 'App\\\Models\\\Wir_description'
                                AND mtm.term_type = 'App\\\Models\\\Prod_routing'
                            GROUP BY mtm_prod_routing_id
                        )
                        ,t3 AS(
                            SELECT po.prod_routing_id AS prod_routing_id
                            ,SUM(po.quantity) AS sum_po_quantity
                            ,COUNT(po.id) AS count_po
                            FROM prod_orders po
                            WHERE 1 = 1
                            #AND po.sub_project_id = 21
                            GROUP BY prod_routing_id
                        )
                        SELECT 
                            CONCAT(SUBSTR(t1.wir_closed_at, 1, 7), '-', '01') AS month
                            ,t1.wir_project_id AS project_id
                            ,t1.wir_sub_project_id AS sub_project_id
                            ,t1.prod_routing_id AS prod_routing_id
                            ,t3.sum_po_quantity AS quantity
                            #,t3.count_po AS count_po
                            ,(COUNT(CASE WHEN t1.status = 'closed' THEN t1.status END)/(t2.count_wir_desc))/t3.count_po AS progress
                        FROM t1, t2 ,t3
                        WHERE 1 = 1
                        AND t1.prod_routing_id = t3.prod_routing_id
                        AND t1.prod_routing_id  = t2.mtm_prod_routing_id
                        AND t1.prod_routing_id  = t2.mtm_prod_routing_id
                        AND SUBSTR(t1.wir_closed_at, 1, 7) = '$month'
                        GROUP BY t1.prod_routing_id, month, project_id, sub_project_id, prod_routing_id";
        return $sql;
    }
}
