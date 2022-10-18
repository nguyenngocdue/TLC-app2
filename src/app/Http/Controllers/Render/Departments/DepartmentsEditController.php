<?php

namespace App\Http\Controllers\Render\Departments;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Department;

class DepartmentsEditController extends CreateEditController
{
    protected $type = 'department';
    protected $data = Department::class;
    protected $action = "edit";
}
