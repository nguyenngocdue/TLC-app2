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
    public function qaqc_insp_chklsts()
    {
        return ['mode_option' => [
            // '010' => 'Inspection Matrix 01',
            '020' => 'Inspection Matrix 02',
        ]];
    }
    public function qaqc_wirs()
    {
        return ['mode_option' => [
            '010' => 'WIR Register',
            '020' => 'WIR Detail Progress',
        ]];
    }
    public function qaqc_ncrs()
    {
        return ['mode_option' => [
            '010' => 'NCR Progress',
        ]];
    }
}
