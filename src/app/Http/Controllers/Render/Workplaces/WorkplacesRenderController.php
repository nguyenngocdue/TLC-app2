<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\RenderController;
use App\Models\Workplace;

class WorkplacesRenderController extends RenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
    protected $permissionMiddleware = [
        'read' => 'read_workplace|edit_workplace|edit_other_workplace|delete_workplace|delete_other_workplace',
        'edit' => 'edit_workplace|edit_other_workplace',
        'delete' => 'delete_workplace|delete_other_workplace'
    ];
}
