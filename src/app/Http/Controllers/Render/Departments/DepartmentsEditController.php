<?php

namespace App\Http\Controllers\Render\Departments;

use App\Http\Controllers\Render\EditController;
use App\Models\Department;

class DepartmentsEditController extends EditController
{
    protected $type = 'department';
    protected $data = Department::class;
}