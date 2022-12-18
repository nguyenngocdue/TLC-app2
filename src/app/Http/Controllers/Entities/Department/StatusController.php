<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Department;

class StatusController extends AbstractStatusController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}