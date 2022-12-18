<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_position2;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}