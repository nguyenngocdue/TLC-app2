<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User_category;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
