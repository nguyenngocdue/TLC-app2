<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\RenderController;
use App\Models\Workplace;

class WorkplacesRenderController extends RenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
    protected $permissionMiddleware = [
        'read' => 'read-workplace|create-workplace|edit-workplace|edit-others-workplace|delete-workplace|delete-others-workplace',
        'edit' => 'edit-workplace|edit-others-workplace',
        'delete' => 'delete-workplace|delete-others-workplace'
    ];
}
