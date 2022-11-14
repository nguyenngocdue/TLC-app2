<?php

namespace App\Http\Controllers\Manage\User_category;

use App\Http\Controllers\Manage\ManagePropController;

class ManageUser_categoryPropController extends ManagePropController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
