<?php

namespace App\Http\Controllers\Reports;

trait TraitDataModesReport
{
    public function prod_orders()
    {
        return ['mode_option' => [
            '010' => 'Total VS Target (Hour/Man-hour)',
        ]];
    }
    public function prod_runs()
    {
        return ['mode_option' => [
            '010' => 'Daily Timesheet',
        ]];
    }
    public function prod_sequences()
    {
        return ['mode_option' => [
            '010' => 'Worker number',
            '020' => 'Total man-hours',
            '030' => 'Work Amount',
            '040' => 'min/UoM',
            '050' => 'Model 050 (updating)',
        ]];
    }
    public function hr_timesheet_lines(){
        return ['mode_option' =>[
            '010' => 'Project/Date',
            '020' => 'Test',
        ]];
    }
}
