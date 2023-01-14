<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Department;

class ListenerController extends AbstractListenerController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
