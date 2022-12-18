<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Department;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
    protected $permissionMiddleware = [
        'read' => 'read-departments',
        'edit' => 'read-departments|create-departments|edit-departments|edit-others-departments',
        'delete' => 'read-departments|create-departments|edit-departments|edit-others-departments|delete-departments|delete-others-departments'
    ];
}