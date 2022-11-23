<?php

namespace App\Http\Controllers\Manage\Department;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Department;

class TablePropController extends ManageTablePropController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
