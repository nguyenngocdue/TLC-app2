<?php

namespace App\Http\Controllers\Render\Qaqc_insp_tmpl_lines;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_tmpl_line;

class Qaqc_insp_tmpl_linesViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $typeModel = Qaqc_insp_tmpl_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_tmpl_lines',
        'edit' => 'read-qaqc_insp_tmpl_lines|create-qaqc_insp_tmpl_lines|edit-qaqc_insp_tmpl_lines|edit-others-qaqc_insp_tmpl_lines',
        'delete' => 'read-qaqc_insp_tmpl_lines|create-qaqc_insp_tmpl_lines|edit-qaqc_insp_tmpl_lines|edit-others-qaqc_insp_tmpl_lines|delete-qaqc_insp_tmpl_lines|delete-others-qaqc_insp_tmpl_lines'
    ];
}