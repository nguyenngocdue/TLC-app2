<?php

namespace App\Http\Controllers\Entities\Priority;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Priority;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'priority';
    protected $typeModel = Priority::class;
    protected $permissionMiddleware = [
        'read' => 'read-priorities',
        'edit' => 'read-priorities|create-priorities|edit-priorities|edit-others-priorities',
        'delete' => 'read-priorities|create-priorities|edit-priorities|edit-others-priorities|delete-priorities|delete-others-priorities'
    ];
}