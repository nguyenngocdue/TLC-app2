<?php

namespace App\Http\Controllers\Entities\Qaqc_mir;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_mir;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_mir';
    protected $typeModel = Qaqc_mir::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_mirs',
        'edit' => 'read-qaqc_mirs|create-qaqc_mirs|edit-qaqc_mirs|edit-others-qaqc_mirs',
        'delete' => 'read-qaqc_mirs|create-qaqc_mirs|edit-qaqc_mirs|edit-others-qaqc_mirs|delete-qaqc_mirs|delete-others-qaqc_mirs'
    ];
}