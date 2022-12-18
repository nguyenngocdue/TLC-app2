<?php

namespace App\Http\Controllers\Entities\User_position_pre;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_position_pre;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}