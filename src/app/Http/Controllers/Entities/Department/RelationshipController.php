<?php

namespace App\Http\Controllers\Entities\Department;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Department;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'department';
    protected $typeModel = Department::class;
}
