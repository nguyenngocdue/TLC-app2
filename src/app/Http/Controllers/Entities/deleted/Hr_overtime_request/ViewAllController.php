<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Hr_overtime_request;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'hr_overtime_request';
    protected $typeModel = Hr_overtime_request::class;
    protected $permissionMiddleware = [
        'read' => 'read-hr_overtime_requests',
        'edit' => 'read-hr_overtime_requests|create-hr_overtime_requests|edit-hr_overtime_requests|edit-others-hr_overtime_requests',
        'delete' => 'read-hr_overtime_requests|create-hr_overtime_requests|edit-hr_overtime_requests|edit-others-hr_overtime_requests|delete-hr_overtime_requests|delete-others-hr_overtime_requests'
    ];
}