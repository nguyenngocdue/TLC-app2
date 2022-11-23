<?php

namespace App\Http\Controllers\Manage\User_discipline;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_discipline;

class TablePropController extends ManageTablePropController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
