<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_position1;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}
