<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Department;

class EditController extends AbstractCreateEditController
{
    protected $type = 'department';
    protected $data = Department::class;
    protected $action = "edit";

}