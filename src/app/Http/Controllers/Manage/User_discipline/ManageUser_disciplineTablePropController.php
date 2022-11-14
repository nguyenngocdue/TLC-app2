<?php

namespace App\Http\Controllers\Manage\User_discipline;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageUser_disciplineTablePropController extends ManageTablePropController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
