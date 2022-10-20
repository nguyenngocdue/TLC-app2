<?php

namespace App\Http\Controllers\Render\Sub_project_statuses;

use App\Http\Controllers\Render\RenderController;
use App\Models\Sub_project_status;

class Sub_project_statusesRenderController extends RenderController
{
    protected $type = 'sub_project_status';
    protected $typeModel = Sub_project_status::class;
    protected $permissionMiddleware = [
        'read' => 'read-sub_project_statuses',
        'edit' => 'read-sub_project_statuses|create-sub_project_statuses|edit-sub_project_statuses|edit-others-sub_project_statuses',
        'delete' => 'read-sub_project_statuses|create-sub_project_statuses|edit-sub_project_statuses|edit-others-sub_project_statuses|delete-sub_project_statuses|delete-others-sub_project_statuses'
    ];
}