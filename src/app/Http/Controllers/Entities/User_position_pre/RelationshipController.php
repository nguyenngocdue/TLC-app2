<?php

namespace App\Http\Controllers\Entities\User_position_pre;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_position_pre;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}
