<?php

//They are term ids in DB
return [
    "document-prod_sequence_050" => [ //PPR Daily Progress
        "white_list" => [62],
        "black_list" => [],
    ],
    "document-prod_sequence_040" => [ //Project Benchmark Report
        "white_list" => [49],
        "black_list" => [], //49: STW Module
    ],
    "document-prod_sequence_010" => [ //4. Target vs. Actual
        "white_list" => [],
        "black_list" => [9], // #9: PPR-MEPF
    ],
];
