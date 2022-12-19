<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Field;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}
