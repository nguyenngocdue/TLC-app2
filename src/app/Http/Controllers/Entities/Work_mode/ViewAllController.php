<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Work_mode;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
    protected $permissionMiddleware = [
        'read' => 'read-work_modes',
        'edit' => 'read-work_modes|create-work_modes|edit-work_modes|edit-others-work_modes',
        'delete' => 'read-work_modes|create-work_modes|edit-work_modes|edit-others-work_modes|delete-work_modes|delete-others-work_modes'
    ];
}