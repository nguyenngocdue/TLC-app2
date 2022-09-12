<?php

namespace App\Http\Controllers\Manage\User;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User;

class ManageUserRelationshipController extends ManageRelationshipController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
