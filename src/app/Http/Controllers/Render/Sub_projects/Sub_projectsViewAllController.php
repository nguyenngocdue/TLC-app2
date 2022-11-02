<?php

namespace App\Http\Controllers\Render\Sub_projects;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Sub_project;

class Sub_projectsViewAllController extends ViewAllController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
    protected $permissionMiddleware = [
        'read' => 'read-sub_projects',
        'edit' => 'read-sub_projects|create-sub_projects|edit-sub_projects|edit-others-sub_projects',
        'delete' => 'read-sub_projects|create-sub_projects|edit-sub_projects|edit-others-sub_projects|delete-sub_projects|delete-others-sub_projects'
    ];
}
