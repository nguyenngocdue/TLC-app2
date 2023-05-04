<?php

return [
    'qaqc_mirs' => [
        'qaqc_ncrs' => [
            'parent_type' => ['type' => 'formValue', 'name' => 'entityParentType',],
            'parent_id' => ['type' => 'formValue', 'name' => 'entityParentId',],
            'project_id' => [],
            'sub_project_id' => [],
            'prod_discipline_id' => [],
        ],
    ],
    'qaqc_insp_chklst_lines' => [
        'qaqc_ncrs' => [
            'parent_type' => ['type' => 'formValue', 'name' => 'entityParentType',],
            'parent_id' => ['type' => 'formValue', 'name' => 'entityParentId',],
            'project_id' => ['type' => 'complexEloquent', 'name' => 'fromChklstLine2Project'],
            'sub_project_id' => ['type' => 'complexEloquent', 'name' => 'fromChklstLine2SubProject'],
            'prod_routing_id' => ['type' => 'complexEloquent', 'name' => 'fromChklstLine2Routing'],
            'prod_order_id' => ['type' => 'complexEloquent', 'name' => 'fromChklstLine2ProdOrder'],
            // 'prod_discipline_id' => '',
        ],
    ],
];
