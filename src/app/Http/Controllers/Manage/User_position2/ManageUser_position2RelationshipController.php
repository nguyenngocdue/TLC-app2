<?php

namespace App\Http\Controllers\Manage\User_position2;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User_position2;

class ManageUser_position2RelationshipController extends ManageRelationshipController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}
