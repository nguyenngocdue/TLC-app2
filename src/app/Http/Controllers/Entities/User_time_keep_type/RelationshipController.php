<?php

namespace App\Http\Controllers\Entities\User_time_keep_type;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_time_keep_type;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
}
