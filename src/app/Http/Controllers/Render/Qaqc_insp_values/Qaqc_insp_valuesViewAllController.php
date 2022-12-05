<?php

namespace App\Http\Controllers\Render\Qaqc_insp_values;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_value;

class Qaqc_insp_valuesViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_value';
    protected $typeModel = Qaqc_insp_value::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_values',
        'edit' => 'read-qaqc_insp_values|create-qaqc_insp_values|edit-qaqc_insp_values|edit-others-qaqc_insp_values',
        'delete' => 'read-qaqc_insp_values|create-qaqc_insp_values|edit-qaqc_insp_values|edit-others-qaqc_insp_values|delete-qaqc_insp_values|delete-others-qaqc_insp_values'
    ];
}