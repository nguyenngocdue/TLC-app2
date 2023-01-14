<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Project;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
    protected $permissionMiddleware = [
        'read' => 'read-projects',
        'edit' => 'read-projects|create-projects|edit-projects|edit-others-projects',
        'delete' => 'read-projects|create-projects|edit-projects|edit-others-projects|delete-projects|delete-others-projects'
    ];
}