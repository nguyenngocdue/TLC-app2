<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_discipline;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
