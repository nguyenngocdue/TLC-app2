<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Department;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}