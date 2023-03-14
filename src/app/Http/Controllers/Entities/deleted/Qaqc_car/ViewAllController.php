<?php

namespace App\Http\Controllers\Entities\Qaqc_car;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Qaqc_car;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'qaqc_car';
    protected $typeModel = Qaqc_car::class;
    protected $permissionMiddleware = [
        'read' => 'read-qaqc_cars',
        'edit' => 'read-qaqc_cars|create-qaqc_cars|edit-qaqc_cars|edit-others-qaqc_cars',
        'delete' => 'read-qaqc_cars|create-qaqc_cars|edit-qaqc_cars|edit-others-qaqc_cars|delete-qaqc_cars|delete-others-qaqc_cars'
    ];
}