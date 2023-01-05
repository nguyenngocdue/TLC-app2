<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_run;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_tmpl_run;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_tmpl_run';
    protected $typeModel = Qaqc_insp_tmpl_run::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_tmpl_runs',
        'edit' => 'read-qaqc_insp_tmpl_runs|create-qaqc_insp_tmpl_runs|edit-qaqc_insp_tmpl_runs|edit-others-qaqc_insp_tmpl_runs',
        'delete' => 'read-qaqc_insp_tmpl_runs|create-qaqc_insp_tmpl_runs|edit-qaqc_insp_tmpl_runs|edit-others-qaqc_insp_tmpl_runs|delete-qaqc_insp_tmpl_runs|delete-others-qaqc_insp_tmpl_runs'
    ];
}