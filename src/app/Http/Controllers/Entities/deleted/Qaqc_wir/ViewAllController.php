<?php

namespace App\Http\Controllers\Entities\Qaqc_wir;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_wir;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_wir';
    protected $typeModel = Qaqc_wir::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_wirs',
        'edit' => 'read-qaqc_wirs|create-qaqc_wirs|edit-qaqc_wirs|edit-others-qaqc_wirs',
        'delete' => 'read-qaqc_wirs|create-qaqc_wirs|edit-qaqc_wirs|edit-others-qaqc_wirs|delete-qaqc_wirs|delete-others-qaqc_wirs'
    ];
}