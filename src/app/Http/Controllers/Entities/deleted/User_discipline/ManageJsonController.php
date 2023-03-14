<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_discipline;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}