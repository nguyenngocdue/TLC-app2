<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\RenderController;
use App\Models\Workplace;

class WorkplacesRenderController extends RenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
    protected $permissionMiddleware = [
        'read' => 'read-workplaces',
        'edit' => 'read-workplaces|create-workplaces|edit-workplaces|edit-others-workplaces',
        'delete' => 'read-workplaces|create-workplaces|edit-workplaces|edit-others-workplaces|delete-workplaces|delete-others-workplaces'
    ];
}
