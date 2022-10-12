<?php

namespace App\Http\Controllers\Render\Departments;

use App\Http\Controllers\Render\EditController;
use App\Models\Department;

class DepartmentsCreateController extends EditController
{
    protected $type = 'department';
    protected $data = Department::class;
    protected $action = "create";
}