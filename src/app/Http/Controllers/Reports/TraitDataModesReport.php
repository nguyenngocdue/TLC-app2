<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\UpdateUserSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TraitDataModesReport
{

    // Documents
    protected function qaqc_insp_chklsts()
    {
        return ['mode_option' => [
            '010' => 'Mode 010 ',
        ]];
    }

    /* Registers */
    protected function hr_overtime_requests()
    {
        return ['mode_option' => [
            '010' => 'Overtime Summary ',
            '020' => 'Overtime User Line'
        ]];
    }
    protected function qaqc_insp_chklst_shts()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    protected function qaqc_wirs()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }

    /* Reports */
    protected function prod_orders()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    protected function prod_runs()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    protected function prod_sequences()
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
