<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Department;

class PropController extends AbstractPropController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
