<?php

namespace App\Http\Controllers\Reports;

trait TraitDataModesReport
{
    public function prod_orders()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    public function prod_runs()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    public function prod_sequences()
    {
        return ['mode_option' => [
            '010' => 'Worker number',
            '020' => 'Total man-hours',
            '030' => 'Work Amount',
            '040' => 'min/UoM',
            '050' => 'Model 050'
        ]];
    }
}
