<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Workplace;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
