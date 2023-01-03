<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Work_area;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}
