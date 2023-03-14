<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_tmpl_line;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $typeModel = Qaqc_insp_tmpl_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_tmpl_lines',
        'edit' => 'read-qaqc_insp_tmpl_lines|create-qaqc_insp_tmpl_lines|edit-qaqc_insp_tmpl_lines|edit-others-qaqc_insp_tmpl_lines',
        'delete' => 'read-qaqc_insp_tmpl_lines|create-qaqc_insp_tmpl_lines|edit-qaqc_insp_tmpl_lines|edit-others-qaqc_insp_tmpl_lines|delete-qaqc_insp_tmpl_lines|delete-others-qaqc_insp_tmpl_lines'
    ];
}