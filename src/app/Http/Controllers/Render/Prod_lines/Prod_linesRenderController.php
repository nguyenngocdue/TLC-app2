<?php

namespace App\Http\Controllers\Render\Prod_lines;

use App\Http\Controllers\Render\RenderController;
use App\Models\Prod_line;

class Prod_linesRenderController extends RenderController
{
    protected $type = 'prod_line';
    protected $typeModel = Prod_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_lines',
        'edit' => 'read-prod_lines|create-prod_lines|edit-prod_lines|edit-others-prod_lines',
        'delete' => 'read-prod_lines|create-prod_lines|edit-prod_lines|edit-others-prod_lines|delete-prod_lines|delete-others-prod_lines'
    ];
}