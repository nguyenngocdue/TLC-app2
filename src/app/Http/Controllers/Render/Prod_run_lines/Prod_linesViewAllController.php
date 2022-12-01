<?php

namespace App\Http\Controllers\Render\Prod_run_lines;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Prod_run_line;

class Prod_run_linesViewAllController extends ViewAllController
{
    protected $type = 'prod_run_line';
    protected $typeModel = Prod_run_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_run_lines',
        'edit' => 'read-prod_run_lines|create-prod_run_lines|edit-prod_run_lines|edit-others-prod_run_lines',
        'delete' => 'read-prod_run_lines|create-prod_run_lines|edit-prod_run_lines|edit-others-prod_run_lines|delete-prod_run_lines|delete-others-prod_run_lines'
    ];
}
