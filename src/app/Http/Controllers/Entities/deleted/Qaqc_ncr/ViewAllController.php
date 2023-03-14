<?php

namespace App\Http\Controllers\Entities\Qaqc_ncr;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_ncr;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_ncr';
    protected $typeModel = Qaqc_ncr::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_ncrs',
        'edit' => 'read-qaqc_ncrs|create-qaqc_ncrs|edit-qaqc_ncrs|edit-others-qaqc_ncrs',
        'delete' => 'read-qaqc_ncrs|create-qaqc_ncrs|edit-qaqc_ncrs|edit-others-qaqc_ncrs|delete-qaqc_ncrs|delete-others-qaqc_ncrs'
    ];
}