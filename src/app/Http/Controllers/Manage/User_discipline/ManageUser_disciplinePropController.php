<?php

namespace App\Http\Controllers\Manage\User_discipline;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_discipline;

class ManageUser_disciplinePropController extends ManagePropController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
