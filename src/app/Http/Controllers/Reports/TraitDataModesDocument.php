<?php

namespace App\Http\Controllers\Reports;

use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;

trait TraitDataModesDocument
{
    // public function qaqc_insp_chklsts()
    // {
    //     return ['mode_option' => [
    //         // '010' => 'Inspection Checklist',
    //     ]];
    // }

    // public function getDataForModeControl($dataSource = [])
    // {
    //     $subProjects = ['sub_project' => Sub_project::get()->pluck('name', 'id')->toArray()];
    //     $prod_orders  = ['prod_order' =>  Prod_order::get()->pluck('name', 'id')->toArray()];
    //     $insp_tmpls = ['qaqc_insp_tmpl' => Qaqc_insp_tmpl::get()->pluck('name', 'id')->toArray()];
    //     $insp_chklsts = ['insp_chklst' => Qaqc_insp_chklst::get()->pluck('name', 'id')->toArray()];
    //     $run_option = ['run_option' => ['View only last run', 'View all runs']];
    //     return array_merge($subProjects, $prod_orders, $insp_tmpls, $insp_chklsts, $run_option);
    // }

    // public function getSettingParamsReport()
    // {
    //     $result = [
    //         [
    //             'name_param' => 'sub_project',
    //             'listen_from' => '',
    //             'name_more' => 'type-report-sub-project',
    //         ],
    //         [
    //             'name_param' => 'prod_order',
    //             'listen_from' => 'sub_project',
    //             'name_more' => 'id-report-prod-order',
    //         ],
    //         [
    //             'name_param' => 'check_sheet',
    //             'listen_from' => 'prod_order',
    //             'name_more' => 'id-report-check-sheet',
    //         ],
    //         [
    //             'name_param' => 'run_option',
    //             'listen_from' => '',
    //             'name_more' => 'type-report-run-history-option',
    //         ]
    //     ];
    //     return $result;
    // }
}
