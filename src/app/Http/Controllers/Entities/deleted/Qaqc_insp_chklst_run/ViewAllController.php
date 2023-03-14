<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_run;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_chklst_run;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_chklst_run';
    protected $typeModel = Qaqc_insp_chklst_run::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_chklst_runs',
        'edit' => 'read-qaqc_insp_chklst_runs|create-qaqc_insp_chklst_runs|edit-qaqc_insp_chklst_runs|edit-others-qaqc_insp_chklst_runs',
        'delete' => 'read-qaqc_insp_chklst_runs|create-qaqc_insp_chklst_runs|edit-qaqc_insp_chklst_runs|edit-others-qaqc_insp_chklst_runs|delete-qaqc_insp_chklst_runs|delete-others-qaqc_insp_chklst_runs'
    ];
}