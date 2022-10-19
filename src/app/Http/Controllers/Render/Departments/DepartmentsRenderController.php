<?php

namespace App\Http\Controllers\Render\Departments;

use App\Http\Controllers\Render\RenderController;
use App\Models\Department;

class DepartmentsRenderController extends RenderController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
    protected $permissionMiddleware = [
        'read' => 'read-departments',
        'edit' => 'read-departments|create-departments|edit-departments|edit-others-departments',
        'delete' => 'read-departments|create-departments|edit-departments|edit-others-departments|delete-departments|delete-others-departments'
    ];
}
