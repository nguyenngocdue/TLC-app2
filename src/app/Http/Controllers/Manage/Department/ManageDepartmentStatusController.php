<?php

namespace App\Http\Controllers\Manage\Department;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Department;

class ManageDepartmentStatusController extends ManageStatusController
{
    protected $type = "department";
    protected $typeModel = Department::class;
}
