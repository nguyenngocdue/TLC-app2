<?php

namespace App\Http\Controllers\Manage\User_position2;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_position2;

class TablePropController extends ManageTablePropController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}
