<?php

namespace App\Http\Controllers\Manage\User_time_keep_type;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User_time_keep_type;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
}
