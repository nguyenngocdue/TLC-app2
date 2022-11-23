<?php

namespace App\Http\Controllers\Manage\User;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User;

class TablePropController extends ManageTablePropController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
