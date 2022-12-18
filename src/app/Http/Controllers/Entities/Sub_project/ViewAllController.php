<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Sub_project;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
    protected $permissionMiddleware = [
        'read' => 'read-sub_projects',
        'edit' => 'read-sub_projects|create-sub_projects|edit-sub_projects|edit-others-sub_projects',
        'delete' => 'read-sub_projects|create-sub_projects|edit-sub_projects|edit-others-sub_projects|delete-sub_projects|delete-others-sub_projects'
    ];
}