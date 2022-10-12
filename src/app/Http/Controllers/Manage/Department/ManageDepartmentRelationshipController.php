<?php

namespace App\Http\Controllers\Manage\Department;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Department;

class ManageDepartmentRelationshipController extends ManageRelationshipController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
