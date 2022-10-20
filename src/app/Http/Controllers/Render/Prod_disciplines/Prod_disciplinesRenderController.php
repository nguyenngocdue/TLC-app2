<?php

namespace App\Http\Controllers\Render\Prod_disciplines;

use App\Http\Controllers\Render\RenderController;
use App\Models\Prod_discipline;

class Prod_disciplinesRenderController extends RenderController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_disciplines',
        'edit' => 'read-prod_disciplines|create-prod_disciplines|edit-prod_disciplines|edit-others-prod_disciplines',
        'delete' => 'read-prod_disciplines|create-prod_disciplines|edit-prod_disciplines|edit-others-prod_disciplines|delete-prod_disciplines|delete-others-prod_disciplines'
    ];
}