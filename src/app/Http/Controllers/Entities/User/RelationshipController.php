<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\User;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
