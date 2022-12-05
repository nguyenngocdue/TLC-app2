<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklst_lines;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_chklst_line;

class Qaqc_insp_chklst_linesViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_chklst_line';
    protected $typeModel = Qaqc_insp_chklst_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_chklst_lines',
        'edit' => 'read-qaqc_insp_chklst_lines|create-qaqc_insp_chklst_lines|edit-qaqc_insp_chklst_lines|edit-others-qaqc_insp_chklst_lines',
        'delete' => 'read-qaqc_insp_chklst_lines|create-qaqc_insp_chklst_lines|edit-qaqc_insp_chklst_lines|edit-others-qaqc_insp_chklst_lines|delete-qaqc_insp_chklst_lines|delete-others-qaqc_insp_chklst_lines'
    ];
}