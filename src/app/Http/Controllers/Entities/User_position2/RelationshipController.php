<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_position2;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}
