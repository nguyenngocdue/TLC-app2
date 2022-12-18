<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Work_mode;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
