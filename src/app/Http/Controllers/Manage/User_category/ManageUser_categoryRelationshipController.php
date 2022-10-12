<?php

namespace App\Http\Controllers\Manage\User_category;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\User_category;

class ManageUser_categoryRelationshipController extends ManageRelationshipController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
