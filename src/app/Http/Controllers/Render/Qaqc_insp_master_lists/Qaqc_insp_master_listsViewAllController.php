<?php

namespace App\Http\Controllers\Render\Qaqc_insp_master_lists;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Qaqc_insp_master_list;

class Qaqc_insp_master_listsViewAllController extends ViewAllController
{
    protected $type = 'qaqc_insp_master_list';
    protected $typeModel = Qaqc_insp_master_list::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_insp_master_lists',
        'edit' => 'read-qaqc_insp_master_lists|create-qaqc_insp_master_lists|edit-qaqc_insp_master_lists|edit-others-qaqc_insp_master_lists',
        'delete' => 'read-qaqc_insp_master_lists|create-qaqc_insp_master_lists|edit-qaqc_insp_master_lists|edit-others-qaqc_insp_master_lists|delete-qaqc_insp_master_lists|delete-others-qaqc_insp_master_lists'
    ];
}