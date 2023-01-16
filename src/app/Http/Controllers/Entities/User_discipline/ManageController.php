<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\User_discipline;

class ManageController extends AbstractManageController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}