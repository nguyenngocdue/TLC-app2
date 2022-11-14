<?php

namespace App\Http\Controllers\Manage\User;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User;

class ManageUserPropController extends ManagePropController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
