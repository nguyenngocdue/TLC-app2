<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_group;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_insp_group;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_insp_group';
    protected $typeModel = Qaqc_insp_group::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_groups',
        'edit' => 'read-qaqc_insp_groups|create-qaqc_insp_groups|edit-qaqc_insp_groups|edit-others-qaqc_insp_groups',
        'delete' => 'read-qaqc_insp_groups|create-qaqc_insp_groups|edit-qaqc_insp_groups|edit-others-qaqc_insp_groups|delete-qaqc_insp_groups|delete-others-qaqc_insp_groups'
    ];
}