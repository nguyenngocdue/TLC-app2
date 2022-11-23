<?php

namespace App\Http\Controllers\Manage\User_discipline;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User_discipline;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
