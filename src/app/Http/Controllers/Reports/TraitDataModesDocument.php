<?php

namespace App\Http\Controllers\Reports;

trait TraitDataModesDocument
{
    public function qaqc_insp_chklsts()
    {
        return ['mode_option' => [
            '010' => 'Mode 010 ',
        ]];
    }
}
