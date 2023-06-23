<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitDemoModeControl
{
    function getDataSource()
    {
        $dataSource = [
            "name_1" => ["BTH1", "BTH2", "ERH", "GHT", "NGH2", "BTH6+", "HLCP1", "HLCP2", "HLCSF", "BTH3"],
            "name_2" => ["HLC-P1-001", "HLC-P1-002", "HLC-P1-003", "HLC-P2-001", "HLC-P2-002", "HLC-P2-003", "HLC-SF-001", "HLC-SF-002"]
        ];
        return $dataSource;
    }

    function getItemsSelected()
    {
        return [
            "name_1" => "5",
            "name_2" => "8"
        ];
    }
}
