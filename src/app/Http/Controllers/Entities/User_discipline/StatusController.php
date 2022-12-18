<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_discipline;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}