<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\RenderController;
use App\Models\Workplace;

class WorkplacesRenderController extends RenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
    protected $permissionMiddleware = [
        'read' => 'read-workplace',
        'edit' => 'read-workplace|create-workplace|edit-workplace|edit-others-workplace',
        'delete' => 'read-workplace|create-workplace|edit-workplace|edit-others-workplace|delete-workplace|delete-others-workplace'
    ];
}
