<?php

namespace App\Http\Controllers\Reports;

trait TraitDataModesRegister
{
    public function hr_overtime_requests()
    {
        return ['mode_option' => [
            '010' => 'Overtime Summary ',
            '020' => 'Overtime User Line'
        ]];
    }
    public function qaqc_insp_chklst_shts()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
    public function qaqc_wirs()
    {
        return ['mode_option' => [
            '010' => 'Mode 10',
        ]];
    }
}
