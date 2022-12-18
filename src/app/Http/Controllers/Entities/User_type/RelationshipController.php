<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_type;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
