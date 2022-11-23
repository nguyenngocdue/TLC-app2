<?php

namespace App\Http\Controllers\Manage\User_type;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User_type;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
