<?php

namespace App\Http\Controllers\Render\Qaqc_insp_control_groups;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_control_group;

class Qaqc_insp_control_groupsViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_control_group';
    protected $typeModel = Qaqc_insp_control_group::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_control_groups',
        'edit' => 'read-qaqc_insp_control_groups|create-qaqc_insp_control_groups|edit-qaqc_insp_control_groups|edit-others-qaqc_insp_control_groups',
        'delete' => 'read-qaqc_insp_control_groups|create-qaqc_insp_control_groups|edit-qaqc_insp_control_groups|edit-others-qaqc_insp_control_groups|delete-qaqc_insp_control_groups|delete-others-qaqc_insp_control_groups'
    ];
}