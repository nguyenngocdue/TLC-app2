<?php

namespace App\Http\Controllers\Manage\User_discipline;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_discipline;

class StatusController extends ManageStatusController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}