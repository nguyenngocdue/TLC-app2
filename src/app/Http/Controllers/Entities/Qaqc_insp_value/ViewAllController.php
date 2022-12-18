<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_value;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_value;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_value';
    protected $typeModel = Qaqc_insp_value::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_values',
        'edit' => 'read-qaqc_insp_values|create-qaqc_insp_values|edit-qaqc_insp_values|edit-others-qaqc_insp_values',
        'delete' => 'read-qaqc_insp_values|create-qaqc_insp_values|edit-qaqc_insp_values|edit-others-qaqc_insp_values|delete-qaqc_insp_values|delete-others-qaqc_insp_values'
    ];
}