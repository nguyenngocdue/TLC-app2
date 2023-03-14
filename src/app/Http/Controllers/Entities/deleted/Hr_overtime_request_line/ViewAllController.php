<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request_line;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Hr_overtime_request_line;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'hr_overtime_request_line';
    protected $typeModel = Hr_overtime_request_line::class;
    protected $permissionMiddleware = [
        'read' => 'read-hr_overtime_request_lines',
        'edit' => 'read-hr_overtime_request_lines|create-hr_overtime_request_lines|edit-hr_overtime_request_lines|edit-others-hr_overtime_request_lines',
        'delete' => 'read-hr_overtime_request_lines|create-hr_overtime_request_lines|edit-hr_overtime_request_lines|edit-others-hr_overtime_request_lines|delete-hr_overtime_request_lines|delete-others-hr_overtime_request_lines'
    ];
}