<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Control_type;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
    protected $permissionMiddleware = [
        'read' => 'read-control_types',
        'edit' => 'read-control_types|create-control_types|edit-control_types|edit-others-control_types',
        'delete' => 'read-control_types|create-control_types|edit-control_types|edit-others-control_types|delete-control_types|delete-others-control_types'
    ];
}