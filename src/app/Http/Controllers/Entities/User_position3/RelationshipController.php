<?php

namespace App\Http\Controllers\Entities\User_position3;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_position3;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_position3';
    protected $typeModel = User_position3::class;
}
