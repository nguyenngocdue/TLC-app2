<?php

namespace App\Http\Controllers\Manage\User_category;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_category;

class TablePropController extends ManageTablePropController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
