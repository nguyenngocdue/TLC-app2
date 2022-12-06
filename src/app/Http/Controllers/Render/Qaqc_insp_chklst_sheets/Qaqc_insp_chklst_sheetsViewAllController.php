<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklst_sheets;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_chklst_sheet;

class Qaqc_insp_chklst_sheetsViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_chklst_sheet';
    protected $typeModel = Qaqc_insp_chklst_sheet::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_chklst_sheets',
        'edit' => 'read-qaqc_insp_chklst_sheets|create-qaqc_insp_chklst_sheets|edit-qaqc_insp_chklst_sheets|edit-others-qaqc_insp_chklst_sheets',
        'delete' => 'read-qaqc_insp_chklst_sheets|create-qaqc_insp_chklst_sheets|edit-qaqc_insp_chklst_sheets|edit-others-qaqc_insp_chklst_sheets|delete-qaqc_insp_chklst_sheets|delete-others-qaqc_insp_chklst_sheets'
    ];
}