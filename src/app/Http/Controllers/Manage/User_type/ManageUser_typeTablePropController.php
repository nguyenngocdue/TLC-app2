<?php

namespace App\Http\Controllers\Manage\User_type;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_type;

class ManageUser_typeTablePropController extends ManageTablePropController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
