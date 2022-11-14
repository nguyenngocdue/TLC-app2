<?php

namespace App\Http\Controllers\Manage\User;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageUserTablePropController extends ManageTablePropController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
