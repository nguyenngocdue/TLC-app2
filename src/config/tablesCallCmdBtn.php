<?php

return [
    'prod_orders' => [
        'qaqc_insp_chklsts' => [
            'CallCommandButtonList' => [
                [
                    'title' => 'Create a QAQC Inspection',
                    'url' => '/api/v1/qaqc/clone_chklst_from_tmpl',
                    'inspTmplId' => 1,
                ],
                [
                    'title' => 'Create a Shipping Module Inspection',
                    'url' => '/api/v1/qaqc/clone_chklst_from_tmpl',
                    'inspTmplId' => 2,
                ],
                [
                    'title' => 'Create a Shipping Container Inspection',
                    'url' => '/api/v1/qaqc/clone_chklst_from_tmpl',
                    'inspTmplId' => 3,
                ],
            ],
        ],
    ],
];
