<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_sheet;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_sheet;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_sheet';
    protected $typeModel = Qaqc_insp_sheet::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_sheets',
        'edit' => 'read-qaqc_insp_sheets|create-qaqc_insp_sheets|edit-qaqc_insp_sheets|edit-others-qaqc_insp_sheets',
        'delete' => 'read-qaqc_insp_sheets|create-qaqc_insp_sheets|edit-qaqc_insp_sheets|edit-others-qaqc_insp_sheets|delete-qaqc_insp_sheets|delete-others-qaqc_insp_sheets'
    ];
}