<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Work_area;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
    protected $permissionMiddleware = [
        'read' => 'read-work_areas',
        'edit' => 'read-work_areas|create-work_areas|edit-work_areas|edit-others-work_areas',
        'delete' => 'read-work_areas|create-work_areas|edit-work_areas|edit-others-work_areas|delete-work_areas|delete-others-work_areas'
    ];
}