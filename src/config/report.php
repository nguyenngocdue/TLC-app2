<?php

//They are term ids in DB
return [
    "report-prod_sequence_020" => [ 
        "white_list" => [
        ],
        "black_list" => [
            'prod_discipline_id' => [9], // #9: PPR-MEPF
        ],
    ],
    "report-prod_sequence_030" => [ 
        "white_list" => [
        ],
        "black_list" => [
            'prod_discipline_id' => [9], // #9: PPR-MEPF
        ],
    ],
    "document-prod_sequence_040" => [ //Project Benchmark Report
        "white_list" => [
            'prod_routing_id' =>[49], //$49 STW Modular
            'prod_discipline_id' => [],
        ],
        "black_list" => [], //49: STW Module
    ],
    "document-prod_sequence_050" => [ //PPR Daily Progress
        "white_list" => [
            'prod_routing_id' =>[62], //PPR Monthly TimeSheet
            'prod_discipline_id' => [],
        ],
        "black_list" => [],
    ],
    "document-prod_sequence_010" => [ //4. Target vs. Actual
        "white_list" => [],
        "black_list" => [
            'prod_routing_id' =>[],
            'prod_discipline_id' => [9], // #9: PPR-MEPF
        ],
    ],
];
