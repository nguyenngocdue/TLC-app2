<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Department;

class ManageController extends AbstractManageController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}