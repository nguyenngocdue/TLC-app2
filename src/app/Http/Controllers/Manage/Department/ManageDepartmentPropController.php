<?php

namespace App\Http\Controllers\Manage\Department;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Department;

class ManageDepartmentPropController extends ManagePropController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
