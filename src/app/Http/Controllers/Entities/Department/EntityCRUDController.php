<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Department;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'department';
    protected $data = Department::class;
}